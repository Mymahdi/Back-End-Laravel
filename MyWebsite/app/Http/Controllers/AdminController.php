<?php

namespace App\Http\Controllers;

use App\Exports\BlogsExport;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller

{
    public function __construct(){
    }

    public function exportBlogs()
    {
        return Excel::download(new BlogsExport, 'WeekBlogs.xlsx');
    }

    public function listExports()
    {
        $directory = 'exports';

        $files = Storage::disk('local')->files($directory);
        $excelFiles = array_filter($files, function ($file) {
            return str_ends_with($file, '.xlsx');
        });
        
        $fileList = array_map(function ($file) {
            return [
                'filename' => basename($file),
                'download_url' => route('exports.download', ['filename' => basename($file)])
            ];
        }, $excelFiles);
        
        return response()->json($fileList);
    }

    public function downloadExportedFile($fileName)
    {
        $filePath = "public/exports/{$fileName}";

        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::download($filePath);
    }
}
