<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Stream file with progress support
     */
    public function stream(Request $request, File $file): StreamedResponse
    {
        $path = storage_path('app/public/' . $file->file_path);
        abort_unless(file_exists($path), 404);
        
        return response()->stream(function() use ($path) {
            $handle = fopen($path, 'rb');
            while (!feof($handle)) {
                echo fread($handle, 8192);
                flush();
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => $file->mime_type ?? 'application/octet-stream',
            'Content-Length' => $file->file_size_bytes,
            'Content-Disposition' => 'attachment; filename="' . ($file->original_filename ?? $file->filename) . '"',
        ]);
    }
    
    /**
     * Get file metadata (AJAX)
     */
    public function metadata(File $file)
    {
        return response()->json([
            'filename' => $file->original_filename ?? $file->filename,
            'size' => $file->getFormattedSize(),
            'size_bytes' => $file->file_size_bytes,
            'format' => strtoupper($file->file_format),
            'mime' => $file->mime_type,
            'download_url' => $file->getDownloadUrl(),
        ]);
    }
}