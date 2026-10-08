<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\Category;
use App\Models\Complaint;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isPetugas = $user && $user->role === 'petugas';
        $userId = $user ? $user->id : null;

        $complaintQuery = Complaint::when($isPetugas, fn($q) => $q->where('assigned_to', $userId));
        $aspirationQuery = Aspiration::query();

        if ($isPetugas) {
            $aspirationQuery->whereRaw('1 = 0');
        }

        $stats = [
            'complaints_total' => (clone $complaintQuery)->count(),
            'complaints_new' => (clone $complaintQuery)->where('status', Complaint::STATUS_BARU)->count(),
            'complaints_processing' => (clone $complaintQuery)->where('status', Complaint::STATUS_DIPROSES)->count(),
            'complaints_completed' => (clone $complaintQuery)->where('status', Complaint::STATUS_SELESAI)->count(),
            'aspirations_total' => (clone $aspirationQuery)->count(),
            'aspirations_new' => (clone $aspirationQuery)->where('status', Aspiration::STATUS_BARU)->count(),
            'aspirations_follow_up' => (clone $aspirationQuery)->where('status', Aspiration::STATUS_DITINDAKLANJUTI)->count(),
            'unassigned' => (clone $complaintQuery)->whereNull('assigned_to')
                ->whereIn('status', [Complaint::STATUS_BARU, Complaint::STATUS_DI_ASSIGN, Complaint::STATUS_DIPROSES])
                ->count(),
            'unassigned_aspirations' => 0,
            'active_tickets' => (clone $complaintQuery)->whereIn('status', [Complaint::STATUS_BARU, Complaint::STATUS_DI_ASSIGN, Complaint::STATUS_DIPROSES])->count()
                + (clone $aspirationQuery)->whereIn('status', [Aspiration::STATUS_BARU, Aspiration::STATUS_DITINDAKLANJUTI])->count(),
            'month_total' => (clone $complaintQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count()
                + (clone $aspirationQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'today_complaints' => (clone $complaintQuery)->whereDate('created_at', today())->count(),
            'today_aspirations' => (clone $aspirationQuery)->whereDate('created_at', today())->count(),
        ];

        $trendLabels = [];
        $trendComplaints = [];
        $trendAspirations = [];

        $year = now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $trendLabels[] = \Carbon\Carbon::create($year, $month, 1)
                ->translatedFormat('M');

            $trendComplaints[] = (clone $complaintQuery)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $trendAspirations[] = (clone $aspirationQuery)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
        }

        $complaintStatusData = [
            'baru' => (clone $complaintQuery)->where('status', Complaint::STATUS_BARU)->count(),
            'diproses' => (clone $complaintQuery)->where('status', Complaint::STATUS_DIPROSES)->count(),
            'selesai' => (clone $complaintQuery)->where('status', Complaint::STATUS_SELESAI)->count(),
            'ditolak' => (clone $complaintQuery)->where('status', Complaint::STATUS_DITOLAK)->count(),
        ];

        $aspirationStatusData = [
            'baru' => (clone $aspirationQuery)->where('status', Aspiration::STATUS_BARU)->count(),
            'ditindaklanjuti' => (clone $aspirationQuery)->where('status', Aspiration::STATUS_DITINDAKLANJUTI)->count(),
            'selesai' => (clone $aspirationQuery)->where('status', Aspiration::STATUS_SELESAI)->count(),
        ];

        $categoryCountRelations = [
            'complaints' => fn($q) => $q->when($isPetugas, fn($q2) => $q2->where('assigned_to', $userId)),
        ];

        if (!$isPetugas) {
            $categoryCountRelations['aspirations'] = fn($q) => $q;
        }

        $topCategories = Category::withCount($categoryCountRelations)
            ->get()
            ->map(function ($cat) {
                $cat->total = $cat->complaints_count + ($cat->aspirations_count ?? 0);
                return $cat;
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        $recentComplaints = (clone $complaintQuery)->with('category')
            ->select('id', 'ticket_number', 'subject', 'status', 'category_id', 'created_at')
            ->selectRaw("'complaint' as type")
            ->latest()
            ->take(5)
            ->get();

        $recentAspirations = (clone $aspirationQuery)->with('category')
            ->select('id', 'ticket_number', 'subject', 'status', 'category_id', 'created_at')
            ->selectRaw("'aspiration' as type")
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = $recentComplaints->concat($recentAspirations)
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        if ($isPetugas) {
            $todayTickets = (clone $complaintQuery)->with('category')
                ->whereDate('created_at', today())
                ->select('id', 'ticket_number', 'subject', 'status', 'category_id', 'created_at')
                ->selectRaw("'complaint' as type")
                ->latest()->get()
                ->concat((clone $aspirationQuery)->with('category')
                    ->whereDate('created_at', today())
                    ->select('id', 'ticket_number', 'subject', 'status', 'category_id', 'created_at')
                    ->selectRaw("'aspiration' as type")
                    ->latest()->get())
                ->sortByDesc('created_at')->values();

            return view('admin.dashboard.officer', compact(
                'stats',
                'complaintStatusData',
                'aspirationStatusData',
                'todayTickets'
            ));
        }

        return view('admin.dashboard.admin', compact(
            'stats',
            'trendLabels',
            'trendComplaints',
            'trendAspirations',
            'complaintStatusData',
            'aspirationStatusData',
            'topCategories',
            'recentTickets'
        ));
    }
}
