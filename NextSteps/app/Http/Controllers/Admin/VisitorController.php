<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\TaskAssignment;
use App\Services\VisitorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
  protected $visitorService;

  public function __construct(VisitorService $visitorService)
  {
    $this->visitorService = $visitorService;
  }

  public function index(Request $request)
  {
      $stages = \DB::table('tbl_follow_up_stages')
          ->orderBy('day_offset', 'asc')
          ->get();

      $query = \App\Models\Visitor::with(['stageStatuses'])
          ->leftJoin(\DB::raw('(
              SELECT visitor_id, MAX(task_assignment_id) as latest_id 
              FROM tbl_task_assignment 
              GROUP BY visitor_id
          ) as latest_log'), 'tbl_visitor.visitor_id', '=', 'latest_log.visitor_id')
          ->leftJoin(
              'tbl_task_assignment', 
              'latest_log.latest_id', 
              '=', 
              'tbl_task_assignment.task_assignment_id'
          )
          ->leftJoin(
              'tbl_user', 
              'tbl_task_assignment.user_id', 
              '=',
              'tbl_user.user_id'
          )
          ->select(
              'tbl_visitor.*', 
              'tbl_user.first_name as v_fname', 
              'tbl_user.last_name as v_lname'
          );

      if ($request->filled('search')) {
          $search = $request->search;
          $query->where(function($q) use ($search) {
              $q->where('tbl_visitor.first_name', 'LIKE', "%{$search}%")
                ->orWhere('tbl_visitor.last_name', 'LIKE', "%{$search}%")
                ->orWhere('tbl_visitor.contact_number', 'LIKE', "%{$search}%");
          });
      }

      $visitors = $query->orderBy('tbl_visitor.visitor_id', 'desc')->paginate(10);
      return view('admin.visitors.visitor', compact('visitors', 'stages'));
  }

  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:xlsx,xls,csv|max:2048'
    ]);

    $this->visitorService->importVisitors($request->file('file'));

    return redirect()->back()->with('success', 'Guests imported successfully.');
  }

  public function assign(Request $request)
  {
    $request->validate([
      'visitor_id' => 'required|exists:tbl_visitor,visitor_id',
      'user_id'    => 'nullable|exists:tbl_user,user_id',
    ]);

    if ($request->user_id) {
      TaskAssignment::create([
        'visitor_id'  => $request->visitor_id,
        'user_id'     => $request->user_id,
        'assigned_at' => now(),
      ]);
      
      return response()->json(['success' => true]);
    }

    return response()->json(['error' => 'No user selected'], 400);
  }

  public function updateStatus(Request $request)
  {
    $request->validate([
      'visitor_id' => 'required|exists:tbl_visitor,visitor_id',
      'status'     => 'required|string'
    ]);

    \App\Models\MessageStatus::updateOrCreate(
      ['visitor_id' => $request->visitor_id],
      ['status' => $request->status]
    );

    return response()->json(['success' => true]);
  }
}