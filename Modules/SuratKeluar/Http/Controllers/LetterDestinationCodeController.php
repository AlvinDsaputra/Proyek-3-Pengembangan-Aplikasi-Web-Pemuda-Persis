<?php

namespace Modules\SuratKeluar\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\SuratKeluar\Http\Requests\LetterDestinationCodeRequest;
use Modules\SuratKeluar\Models\LetterDestinationCode;

class LetterDestinationCodeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', LetterDestinationCode::class);

        $destinationCodes = LetterDestinationCode::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('surat-keluar.master.destination-codes.index', compact('destinationCodes'));
    }

    public function create(): View
    {
        $this->authorize('create', LetterDestinationCode::class);

        return view('surat-keluar.master.destination-codes.create');
    }

    public function store(LetterDestinationCodeRequest $request): RedirectResponse
    {
        $this->authorize('create', LetterDestinationCode::class);

        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', true);

        LetterDestinationCode::create($data);

        return redirect()
            ->route('surat-keluar.master.destination-codes.index')
            ->with('success', 'Indeks kode tujuan surat berhasil ditambahkan.');
    }

    public function edit(LetterDestinationCode $destinationCode): View
    {
        $this->authorize('update', $destinationCode);

        return view('surat-keluar.master.destination-codes.edit', compact('destinationCode'));
    }

    public function update(LetterDestinationCodeRequest $request, LetterDestinationCode $destinationCode): RedirectResponse
    {
        $this->authorize('update', $destinationCode);

        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        $destinationCode->update($data);

        return redirect()
            ->route('surat-keluar.master.destination-codes.index')
            ->with('success', 'Indeks kode tujuan surat berhasil diperbarui.');
    }

    public function destroy(LetterDestinationCode $destinationCode): RedirectResponse
    {
        $this->authorize('delete', $destinationCode);

        $destinationCode->delete();

        return redirect()
            ->route('surat-keluar.master.destination-codes.index')
            ->with('success', 'Indeks kode tujuan surat berhasil dihapus.');
    }
}