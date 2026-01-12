<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StorageController extends Controller
{
    /**
     * Serve files from the storage directory
     * Falls back to serving the first available file in the category if the exact file doesn't exist
     */
    public function serveFile($category, $filename)
    {
        $basePath = "leads/$category";
        $requestedPath = "$basePath/$filename";

        // Check if the exact file exists
        if (Storage::disk('public')->exists($requestedPath)) {
            return $this->downloadFile($requestedPath);
        }

        // If the requested file doesn't exist, try to find any file in that category
        try {
            $files = Storage::disk('public')->files($basePath);
            if (!empty($files)) {
                // Serve the first available file in this category
                return $this->downloadFile($files[0]);
            }
        } catch (\Exception $e) {
            // Category doesn't exist
        }

        // File not found
        abort(404, 'File not found');
    }

    /**
     * Download a file from storage
     */
    private function downloadFile($path)
    {
        $fullPath = Storage::disk('public')->path($path);
        
        if (!File::exists($fullPath)) {
            abort(404, 'File not found');
        }

        $filename = basename($path);
        return response()->file($fullPath, [
            'Content-Disposition' => "inline; filename=\"{$filename}\""
        ]);
    }
}
