<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    // --- BACKUP METHOD ---
    public function backup()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d_H-i-s') . ".sql";
        $tables = DB::select('SHOW TABLES');
        $output = "";

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            
            // Get Create Table Statement
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
            
            // Add DROP TABLE IF EXISTS to prevent "Table already exists" errors in future
            $output .= "\n\nDROP TABLE IF EXISTS `$tableName`;\n";
            $output .= $createTable[0]->{'Create Table'} . ";\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    return $value === null ? "NULL" : "'" . addslashes($value) . "'";
                }, (array) $row);
                
                $output .= "INSERT INTO `$tableName` VALUES (" . implode(", ", $values) . ");\n";
            }
        }

        return Response::make($output, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    // --- RESTORE METHOD (FIXED) ---
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt',
        ]);

        try {
            $sql = file_get_contents($request->file('backup_file')->getRealPath());
            
            // Disable Foreign Key Checks (Crucial!)
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            //  WIPE DATABASE: Drop all existing tables before restoring
            // This prevents the "Table already exists" error
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                DB::statement("DROP TABLE IF EXISTS `$tableName`");
            }

            //  Run the Backup SQL
            DB::unprepared($sql);

            //  Re-enable Foreign Key Checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'Database restored successfully!');
        } catch (\Exception $e) {
            // Re-enable FK checks just in case it failed
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'Error restoring database: ' . $e->getMessage());
        }
    }

    // --- GENERAL CONFIGURATION METHODS ---
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_user,email,' . $user->user_id . ',user_id',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Check against custom password_hash column
        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Update password using custom column
        $user->update([
            'password_hash' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password_confirmation' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_confirmation, $user->password_hash)) {
            return back()->with('error', 'Incorrect password. Account deletion cancelled.');
        }

        // Soft delete the user
        $user->update(['is_deleted' => 1, 'status' => 'inactive']);
        
        Auth::logout();
        
        return redirect()->route('login')->with('success', 'Your account has been deleted.');
    }
}