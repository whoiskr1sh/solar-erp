<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class SyncDocumentFilePaths extends Command
{
    protected $signature = 'documents:sync-paths';
    protected $description = 'Sync document file paths from storage to database';

    public function handle()
    {
        $this->info('Starting document file path synchronization...');
        
        $baseDir = 'leads';
        $documents = Document::all();
        $fixed = 0;
        $missing = 0;

        foreach ($documents as $document) {
            // Check if the current file path exists
            if (Storage::disk('public')->exists($document->file_path)) {
                $this->line("✅ {$document->title} - File exists");
                continue;
            }

            // Try to find the file by category subdirectory
            $category = $document->category ?? 'other';
            $searchPath = "$baseDir/$category";
            
            // Check if the file exists in storage with a different name
            if (!Storage::disk('public')->exists($searchPath)) {
                $this->line("❌ {$document->title} - Directory not found: $searchPath");
                $missing++;
                continue;
            }

            // Get all files in this category
            $files = Storage::disk('public')->files($searchPath);
            
            if (empty($files)) {
                $this->line("⚠️ {$document->title} - No files found in $searchPath");
                $missing++;
                continue;
            }

            // Find the first file (or you could implement better matching logic)
            $newFilePath = $files[0];
            $this->line("🔧 {$document->title}");
            $this->line("   Old path: {$document->file_path}");
            $this->line("   New path: $newFilePath");
            
            // Update the document with the correct path
            $document->update([
                'file_path' => $newFilePath,
                'file_name' => basename($newFilePath),
            ]);
            
            $fixed++;
        }

        $this->newLine();
        $this->info("Synchronization complete!");
        $this->info("Fixed: $fixed | Missing: $missing | Total: {$documents->count()}");
    }
}
