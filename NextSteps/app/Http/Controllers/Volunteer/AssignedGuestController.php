<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\MessageStatus; // Mahalaga para sa updateOrCreate
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignedGuestController extends Controller
{
    /**
     * Ipakita ang listahan ng guests na assigned sa volunteer.
     */
    public function index(Request $request)
    {
        $stages = DB::table('tbl_follow_up_stages')
            ->orderBy('day_offset', 'asc')
            ->get();

        // I-filter ang visitors base sa logged-in volunteer ID
        $query = Visitor::with(['stageStatuses'])
            ->whereHas('taskAssignments', function($q) {
                $q->where('user_id', Auth::id());
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        $visitors = $query->orderBy('visitor_id', 'desc')->paginate(10);

        return view('volunteer.assigned_guests.index', 
            compact('visitors', 'stages'));
    }

    /**
     * I-update ang status ng guest per specific stage.
     */
    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'visitor_id' => 'required|integer',
                'status'     => 'required|string',
                'stage_id'   => 'required|integer'
            ]);

            MessageStatus::updateOrCreate(
                [
                    'visitor_id' => $request->visitor_id,
                    'follow_up_stage_id' => $request->stage_id 
                ],
                [
                    'status' => $request->status
                ]
            );

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}