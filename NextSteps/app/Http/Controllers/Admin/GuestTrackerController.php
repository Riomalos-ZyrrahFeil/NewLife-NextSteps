<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\MessageStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestTrackerController extends Controller
{
    public function index(Request $request)
    {
        $stages = \DB::table('tbl_follow_up_stages')
        ->orderBy('day_offset', 'asc') // This ensures Day 1 comes before Day 5
        ->get();

        $visitors = \App\Models\Visitor::with('messageStatus')
        ->orderBy('visitor_id', 'desc')
        ->paginate(10);

        return view('admin.visitors.tracker', compact('visitors', 'stages'));
    }

  public function updateStatus(Request $request)
  {
    $request->validate([
      'visitor_id' => 'required|exists:tbl_visitor,visitor_id',
      'status'     => 'required|string'
    ]);

    // Update or create status in tbl_message_status
    MessageStatus::updateOrCreate(
      ['visitor_id' => $request->visitor_id],
      ['status' => $request->status]
    );

    return response()->json(['success' => true]);
  }
}