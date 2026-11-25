<?php

namespace Modules\SharedCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\SharedCore\Http\Requests\UploadSignatureRequest;
use Modules\SharedCore\Services\SignatureService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SignatureController extends Controller
{
    public function __construct(
        private readonly SignatureService $signatureService,
    ) {}

    public function edit(Request $request): View
    {
        $user = $request->user();
        $signature = $this->signatureService->getActiveForUser($user);
        $signatureUrl = ($signature && $signature->existsInStorage())
            ? route('shared.signature.preview', [
                'v' => $signature->updated_at?->timestamp ?? $signature->id,
            ])
            : null;

        return view('shared-core::signature.edit', [
            'signature'    => $signature,
            'signatureUrl' => $signatureUrl,
            'historyCount' => $user->signatureImages()->count(),
            'user'         => $user,
            'userRoleName' => $user->roles()->first()?->name ?? 'Pengurus',
        ]);
    }

    public function store(UploadSignatureRequest $request): RedirectResponse
    {
        try {
            $this->signatureService->uploadForUser(
                $request->user(),
                $request->file('signature')
            );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('shared.signature.edit')
                ->with('error', 'Terjadi kendala saat menyimpan tanda tangan. Silakan coba lagi.');
        }

        return redirect()
            ->route('shared.signature.edit')
            ->with('success', 'Tanda tangan berhasil disimpan.');
    }

    public function preview(Request $request): StreamedResponse|BinaryFileResponse
    {
        $signature = $this->signatureService->getActiveForUser($request->user());

        if (! $signature || ! $signature->existsInStorage()) {
            abort(404);
        }

        Gate::authorize('view', $signature);

        return Storage::disk('local')->response(
            $signature->file_path,
            'signature.png',
            [
                'Content-Type'           => 'image/png',
                'Cache-Control'          => 'private, no-store, max-age=0',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $signature = $this->signatureService->getActiveForUser($user);

        if (! $signature) {
            return redirect()
                ->route('shared.signature.edit')
                ->with('error', 'Belum ada tanda tangan aktif yang dapat dinonaktifkan.');
        }

        Gate::authorize('delete', $signature);

        $removed = $this->signatureService->removeActiveForUser($user);

        if (! $removed) {
            return redirect()
                ->route('shared.signature.edit')
                ->with('error', 'Belum ada tanda tangan aktif yang dapat dinonaktifkan.');
        }

        return redirect()
            ->route('shared.signature.edit')
            ->with('success', 'Tanda tangan aktif berhasil dinonaktifkan.');
    }
}
