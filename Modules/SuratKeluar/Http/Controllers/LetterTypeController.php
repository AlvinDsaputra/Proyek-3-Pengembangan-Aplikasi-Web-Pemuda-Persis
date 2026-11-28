<?php

namespace Modules\SuratKeluar\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\SuratKeluar\Http\Requests\LetterTypeRequest;
use Modules\SuratKeluar\Models\LetterType;

class LetterTypeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', LetterType::class);

        $letterTypes = LetterType::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })
            ->withCount('templates')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('surat-keluar.master.letter-types.index', compact('letterTypes'));
    }

    public function create(): View
    {
        $this->authorize('create', LetterType::class);

        return view('surat-keluar.master.letter-types.create');
    }

    public function store(LetterTypeRequest $request): RedirectResponse
    {
        $this->authorize('create', LetterType::class);

        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', true);

        LetterType::create($data);

        return redirect()
            ->route('surat-keluar.master.letter-types.index')
            ->with('success', 'Master jenis surat berhasil ditambahkan.');
    }

    public function edit(LetterType $letterType): View
    {
        $this->authorize('update', $letterType);

        return view('surat-keluar.master.letter-types.edit', compact('letterType'));
    }

    public function update(LetterTypeRequest $request, LetterType $letterType): RedirectResponse
    {
        $this->authorize('update', $letterType);

        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        $letterType->update($data);

        return redirect()
            ->route('surat-keluar.master.letter-types.index')
            ->with('success', 'Master jenis surat berhasil diperbarui.');
    }

    public function destroy(LetterType $letterType): RedirectResponse
    {
        $this->authorize('delete', $letterType);

        try {
            $letterType->delete();

            return redirect()
                ->route('surat-keluar.master.letter-types.index')
                ->with('success', 'Master jenis surat berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()
                ->route('surat-keluar.master.letter-types.index')
                ->with('error', 'Jenis surat tidak dapat dihapus karena sudah digunakan pada arsip surat keluar. Silakan nonaktifkan statusnya.');
        }
    }
}