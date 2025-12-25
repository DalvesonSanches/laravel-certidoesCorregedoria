<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;

class CertidaoStorageService{
    public function salvarPdfEmMemoriaNoMinio(string $nomeArquivo, string $pdfBinario): void
    {
        $path = 'certidoes/' . $nomeArquivo;
        $ok = Storage::disk('s3')->put($path, $pdfBinario, [
            'ContentType' => 'application/pdf',
            'ACL'         => 'private',
        ]);
        if (!$ok || !Storage::disk('s3')->exists($path)) {
            throw new \Exception('Falha ao salvar PDF no MinIO.');
        }
    }
}