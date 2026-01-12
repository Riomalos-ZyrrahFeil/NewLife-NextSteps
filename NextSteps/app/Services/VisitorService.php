<?php

namespace App\Services;

use App\Models\Visitor;
use App\Models\User;
use App\Imports\VisitorsImport;
use Maatwebsite\Excel\Facades\Excel;

class VisitorService
{
  /**
   * Import visitors from Excel/CSV file.
   */
  public function importVisitors($file)
  {
    return Excel::import(new VisitorsImport, $file);
  }

  /**
   * Assign a volunteer to a guest.
   */
  public function assignVolunteer($visitorId, $volunteerId)
  {
    $visitor = Visitor::findOrFail($visitorId);
    return $visitor->update([
      'assigned_volunteer_id' => $volunteerId,
      'status' => 'assigned'
    ]);
  }

  /**
   * Get active volunteers for assignment.
   */
  public function getActiveVolunteers()
  {
    return User::where('role', 'volunteer')
      ->where('status', 'active')
      ->get();
  }

  public function updateStatus($visitorId, $status)
  {
    return DB::table('tbl_message_status')
        ->updateOrInsert(
            ['visitor_id' => $visitorId],
            ['status' => $status, 'updated_at' => now()]
        );
  }
}