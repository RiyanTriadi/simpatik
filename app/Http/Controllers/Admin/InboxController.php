<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $isPetugas = auth()->user()->role === 'petugas';

        $complaintsQ = DB::table('complaints')
            ->select(
                'complaints.id',
                DB::raw("'complaint' as type"),
                'complaints.ticket_number',
                'complaints.subject',
                'complaints.category_id',
                'complaints.status',
                'complaints.priority',
                'complaints.assigned_to',
                'complaints.created_at',
                'c.name as category_name',
                'u.name as officer_name',
                'u.profile_image_path as officer_image',
            )
            ->leftJoin('categories as c', 'c.id', '=', 'complaints.category_id')
            ->leftJoin('users as u', 'u.id', '=', 'complaints.assigned_to')
            ->whereNull('complaints.deleted_at');

        // Petugas hanya lihat complaint yang di-assign ke dia
        if ($isPetugas) {
            $complaintsQ->where('complaints.assigned_to', auth()->id());
        }

        // ==================================================
        // 2. Sub-query Aspirations
        // ==================================================
        $aspirationsQ = DB::table('aspirations')
            ->select(
                'aspirations.id',
                DB::raw("'aspiration' as type"),
                'aspirations.ticket_number',
                'aspirations.subject',
                'aspirations.category_id',
                'aspirations.status',
                DB::raw('NULL as priority'),
                DB::raw('NULL as assigned_to'),
                'aspirations.created_at',
                'c.name as category_name',
                DB::raw('NULL as officer_name'),
                DB::raw('NULL as officer_image'),
            )
            ->leftJoin('categories as c', 'c.id', '=', 'aspirations.category_id')
            ->whereNull('aspirations.deleted_at');

        if ($isPetugas) {
            $aspirationsQ->whereRaw('1 = 0');
        }

        $union = $complaintsQ->unionAll($aspirationsQ);
        $query = DB::query()->fromSub($union, 'tickets');

        if ($request->filled('type') && $request->type !== 'semua') {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('priority') && $request->priority !== 'semua') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case '7days':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case '30days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->whereDate('created_at', '>=', $request->date_from);
                    }
                    if ($request->filled('date_to')) {
                        $query->whereDate('created_at', '<=', $request->date_to);
                    }
                    break;
            }
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } elseif ($request->assigned_to === 'assigned') {
                $query->whereNotNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'LIKE', $searchTerm)
                    ->orWhere('ticket_number', 'LIKE', $searchTerm);
            });
        }

        $tickets = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $categories = Category::active()
            ->whereIn('type', [Category::TYPE_PENGADUAN, Category::TYPE_ASPIRASI, Category::TYPE_KEDUANYA])
            ->orderBy('name')
            ->get();

        $officers = User::where('role', User::ROLE_PETUGAS)
            ->orderBy('name')
            ->get();

        $counts = $this->getCounts($isPetugas);

        return view('admin.inbox.index', compact('tickets', 'categories', 'officers', 'counts'));
    }

    private function getCounts(bool $isPetugas = false): array
    {
        $complaintBase = DB::table('complaints')->whereNull('deleted_at');
        $aspirationBase = DB::table('aspirations')->whereNull('deleted_at');

        if ($isPetugas) {
            $complaintBase->where('assigned_to', auth()->id());
            $aspirationBase->whereRaw('1 = 0');
        }

        return [
            'all' => (clone $complaintBase)->count() + (clone $aspirationBase)->count(),
            'baru' => (clone $complaintBase)->where('status', 'baru')->count()
                + (clone $aspirationBase)->where('status', 'baru')->count(),
            'di_assign' => (clone $complaintBase)->where('status', 'di_assign')->count(),
            'diproses' => (clone $complaintBase)->where('status', 'diproses')->count(),
            'selesai' => (clone $complaintBase)->where('status', 'selesai')->count(),
            'ditolak' => (clone $complaintBase)->where('status', 'ditolak')->count(),
            'dibaca' => (clone $aspirationBase)->where('status', 'dibaca')->count(),
            'ditindaklanjuti' => (clone $aspirationBase)->where('status', 'ditindaklanjuti')->count(),
        ];
    }
}