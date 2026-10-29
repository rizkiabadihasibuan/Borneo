<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportRecipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportRecipientController extends Controller
{
    /**
     * Tampilkan direktori penerima laporan (Instansi & MPA) dengan filter dan pencarian.
     */
    public function index(Request $request): View
    {
        $query = ReportRecipient::query()->latest();

        if ($request->filled('q')) {
            $search = trim($request->query('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('agency_type', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('province')) {
            $query->where('province', $request->query('province'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('channel')) {
            $ch = $request->query('channel');
            $query->whereJsonContains('verified_channels', $ch);
        }

        $recipients = $query->paginate(12)->withQueryString();

        $provinces = [
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Semua Wilayah',
        ];

        return view('admin.recipients.index', compact('recipients', 'provinces'));
    }

    /**
     * Form tambah penerima baru.
     */
    public function create(): View
    {
        $provinces = [
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Semua Wilayah',
        ];

        return view('admin.recipients.create', compact('provinces'));
    }

    /**
     * Simpan penerima baru ke direktori.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['pemerintah', 'komunitas_mpa'])],
            'agency_type' => ['required', 'string', 'max:150'],
            'province' => ['required', 'string', 'max:100'],
            'regency' => ['nullable', 'string', 'max:100'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'preferred_channel' => ['required', Rule::in(['email', 'whatsapp', 'manual'])],
            'verified_channels' => ['nullable', 'array'],
            'verified_channels.*' => [Rule::in(['email', 'whatsapp'])],
            'is_active' => ['boolean'],
        ], [
            'name.required' => 'Nama resmi instansi atau kelompok MPA wajib diisi.',
            'category.required' => 'Kategori penerima wajib dipilih.',
            'agency_type.required' => 'Tipe entitas / instansi wajib diisi.',
            'province.required' => 'Wilayah provinsi wajib ditentukan.',
            'phone.required' => 'Nomor telepon / kontak darurat wajib diisi.',
            'email.email' => 'Format email resmi tidak valid.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['verified_channels'] = $request->input('verified_channels', []);

        ReportRecipient::create($validated);

        return redirect()->route('admin.recipients.index')
            ->with('success_message', "Penerima laporan '{$validated['name']}' berhasil ditambahkan ke direktori.");
    }

    /**
     * Tampilkan detail penerima beserta riwayat disposisi yang pernah diterima.
     */
    public function show(ReportRecipient $recipient): View
    {
        $recipient->load(['dispatches.report']);
        return view('admin.recipients.show', compact('recipient'));
    }

    /**
     * Form edit penerima.
     */
    public function edit(ReportRecipient $recipient): View
    {
        $provinces = [
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Semua Wilayah',
        ];

        return view('admin.recipients.edit', compact('recipient', 'provinces'));
    }

    /**
     * Update data penerima di direktori.
     */
    public function update(Request $request, ReportRecipient $recipient): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['pemerintah', 'komunitas_mpa'])],
            'agency_type' => ['required', 'string', 'max:150'],
            'province' => ['required', 'string', 'max:100'],
            'regency' => ['nullable', 'string', 'max:100'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'preferred_channel' => ['required', Rule::in(['email', 'whatsapp', 'manual'])],
            'verified_channels' => ['nullable', 'array'],
            'verified_channels.*' => [Rule::in(['email', 'whatsapp'])],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['verified_channels'] = $request->input('verified_channels', []);

        $recipient->update($validated);

        return redirect()->route('admin.recipients.index')
            ->with('success_message', "Data penerima '{$recipient->name}' berhasil diperbarui.");
    }

    /**
     * Toggle status aktif/nonaktif penerima.
     */
    public function toggle(ReportRecipient $recipient): RedirectResponse
    {
        $recipient->update([
            'is_active' => !$recipient->is_active,
        ]);

        $statusStr = $recipient->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';

        return back()->with('success_message', "Status penerima '{$recipient->name}' berhasil {$statusStr}.");
    }
}
