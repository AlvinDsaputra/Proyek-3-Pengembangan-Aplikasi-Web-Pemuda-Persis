<?php

namespace Modules\SuratKeluar\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\SuratKeluar\Http\Requests\LetterTemplateRequest;
use Modules\SuratKeluar\Models\LetterTemplate;
use Modules\SuratKeluar\Models\LetterType;

class LetterTemplateController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', LetterTemplate::class);

        $templates = LetterTemplate::with('letterType')
            ->when($request->filled('letter_type_id'), fn ($q) => $q->where('letter_type_id', $request->letter_type_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('default_subject', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $letterTypes = LetterType::orderBy('id')->get();

        return view('surat-keluar.master.templates.index', compact('templates', 'letterTypes'));
    }

    public function create(): View
    {
        $this->authorize('create', LetterTemplate::class);

        $letterTypes = LetterType::active()->orderBy('id')->get();

        return view('surat-keluar.master.templates.create', compact('letterTypes'));
    }

    public function store(LetterTemplateRequest $request): RedirectResponse
    {
        $this->authorize('create', LetterTemplate::class);

        $template = new LetterTemplate($request->validated());
        $template->is_active = $request->boolean('is_active', true);
        $template->created_by = $request->user()?->id;
        $template->save();

        return redirect()
            ->route('surat-keluar.master.templates.index')
            ->with('success', 'Template format surat berhasil ditambahkan.');
    }

    public function edit(LetterTemplate $template): View
    {
        $this->authorize('update', $template);

        $letterTypes = LetterType::orderBy('id')->get();

        return view('surat-keluar.master.templates.edit', compact('template', 'letterTypes'));
    }

    public function update(LetterTemplateRequest $request, LetterTemplate $template): RedirectResponse
    {
        $this->authorize('update', $template);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $template->update($data);

        return redirect()
            ->route('surat-keluar.master.templates.index')
            ->with('success', 'Template format surat berhasil diperbarui.');
    }

    public function destroy(LetterTemplate $template): RedirectResponse
    {
        $this->authorize('delete', $template);

        $template->delete();

        return redirect()
            ->route('surat-keluar.master.templates.index')
            ->with('success', 'Template format surat berhasil dihapus.');
    }

    /**
     * Endpoint JSON untuk Fetch API di form draf surat (DEV-03).
     */
    public function getByLetterType(LetterType $letterType): JsonResponse
    {
        $templates = $letterType->templates()
            ->active()
            ->select(['id', 'letter_type_id', 'name', 'default_subject', 'body_template'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'letter_type' => [
                'id' => $letterType->id,
                'code' => $letterType->code,
                'name' => $letterType->name,
            ],
            'templates' => $templates,
        ]);
    }
}