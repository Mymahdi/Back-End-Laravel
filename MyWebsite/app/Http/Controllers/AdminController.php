<?php

namespace App\Http\Controllers;

use App\Exports\BlogsExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller

{
    public function __construct(){
    }

    public function exportAllBlogs(): BinaryFileResponse
    {
        // Define a start and end date, due to avoiding the duplicate code
        //othewise its not hard to implement new Export file
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        return Excel::download(new BlogsExport($startDate, $endDate), 'all_blogs_export.xlsx');
    }

    public function listExports(): JsonResponse
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

    public function download($fileName): mixed
    {
        $filePath = "exports/$fileName.xlsx";
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        return Storage::download($filePath);
    }

    public function changeUserRole($userId): JsonResponse
    {
        $user = User::findOrFail($userId);
        $user->role = 'author';
        $user->save();
        return response()->json(['success' => 'User role changed to author'], 200);
    }
}
