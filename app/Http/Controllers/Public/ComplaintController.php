<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ComplaintStoreRequest;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->whereIn('type', ['pengaduan', 'keduanya'])
            ->orderBy('name', 'asc')
            ->get();

        return view('public.complaint', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComplaintStoreRequest $request)
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous');

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('complaints/attachments', 'public');
        }

        $complaint = Complaint::create([
            'ticket_number' => $this->generateTicketNumber(),
            'creator_type' => $validated['creator_type'],
            'category_id' => $validated['category_id'],
            'is_anonymous' => $isAnonymous,
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'incident_date' => $validated['incident_date'],
            'incident_location' => $validated['incident_location'],
            'attachment_path' => $attachmentPath,
            'reporter_name' => $isAnonymous ? null : $validated['reporter_name'],
            'reporter_phone' => $isAnonymous ? null : $validated['reporter_phone'],
            'reporter_email' => $validated['reporter_email'] ?? null,
        ]);

        User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_PETUGAS])
            ->each(fn (User $user) => $user->notify(new SystemNotification(
                'Pengaduan baru',
                "Tiket {$complaint->ticket_number} membutuhkan perhatian.",
                route($user->role . '.pengaduan.show', $complaint->ticket_number),
            )));

        return redirect()
            ->route('public.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirim. Nomor tiket Anda: ' . $complaint->ticket_number);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComplaintStoreRequest $request, string $id)
    {
        //
    }

    /**
     * Generate a unique complaint ticket number.
     */
    private function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'PGD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Complaint::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }
}
