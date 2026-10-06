<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\AspirationStoreRequest;
use App\Models\Aspiration;
use App\Models\Category;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Str;

class AspirationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->whereIn('type', ['aspirasi', 'keduanya'])
            ->orderBy('name', 'asc')
            ->get();

        return view('public.aspiration', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AspirationStoreRequest $request)
    {
        $validated = $request->validated();
        $isAnonymous = $request->boolean('is_anonymous');

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('aspirations/attachments', 'public');
        }

        $aspiration = Aspiration::create([
            'ticket_number' => $this->generateTicketNumber(),
            'creator_type' => $validated['creator_type'],
            'category_id' => $validated['category_id'] ?? null,
            'is_anonymous' => $isAnonymous,
            'subject' => $validated['subject'] ?? null,
            'description' => $validated['description'],
            'attachment_path' => $attachmentPath,
            'reporter_name' => $isAnonymous ? null : $validated['reporter_name'],
            'reporter_phone' => $isAnonymous ? null : $validated['reporter_phone'],
            'reporter_email' => $validated['reporter_email'] ?? null,
        ]);

        User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_PETUGAS])
            ->each(fn (User $user) => $user->notify(new SystemNotification(
                'Aspirasi baru',
                "Tiket {$aspiration->ticket_number} membutuhkan perhatian.",
                route($user->role . '.aspirasi.show', $aspiration->ticket_number),
            )));

        return redirect()
            ->route('public.aspirasi.index')
            ->with('success', 'Aspirasi berhasil dikirim. Nomor tiket Anda: ' . $aspiration->ticket_number);
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
    public function update(AspirationStoreRequest $request, string $id)
    {
        //
    }

    /**
     * Generate a unique aspiration ticket number.
     */
    private function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'ASP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Aspiration::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }
}
