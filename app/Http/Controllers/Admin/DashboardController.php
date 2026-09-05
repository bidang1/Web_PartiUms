<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\Sponsor;
use App\Models\SubEvent;
use App\Models\TimelineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $year = session('active_year', config('parti.active_year', 2026));

        $stats = [
            'sub_events_count' => SubEvent::forYear($year)->notDeleted()->count(),
            'timeline_count' => TimelineItem::forYear($year)->count(),
            'sponsors_count' => Sponsor::forYear($year)->count(),
        ];

        $recentLogs = Auth::user()->role === 'SUPERADMIN' 
            ? AuditLog::with('user')->orderBy('created_at', 'desc')->take(5)->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'recentLogs', 'year'));
    }

    public function changeYear(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2050',
        ]);

        $year = (int) $request->year;
        session(['active_year' => $year]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Mengalihkan filter tahun kerja admin ke PARTI ' . $year,
            'entity_type' => 'Session',
            'entity_id' => $year,
        ]);

        return back()->with('success', 'Filter ruang kerja admin dialihkan ke PARTI ' . $year);
    }
}
