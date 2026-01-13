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
            ->orderBy('day_offset', 'asc')
            ->get();

        $query = \App\Models\Visitor::with(['messageStatus']);

        // Pag-click ng "View Tracker", dito papasok ang pangalan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where(\DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$search}%")
                  ->orWhere('contact_number', 'LIKE', "%{$search}%");
            });
        }

        $visitors = $query->orderBy('visitor_id', 'desc')->paginate(10);

        return view('admin.visitors.tracker', compact('visitors', 'stages'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|integer',
            'status'     => 'required|string',
            'stage_id'   => 'required|integer'
        ]);

        \App\Models\MessageStatus::updateOrCreate(
            [
                'visitor_id' => $request->visitor_id,
                'follow_up_stage_id' => $request->stage_id
            ],
            [
                'status' => $request->status
            ]
        );

        return response()->json(['success' => true]);
    }
}