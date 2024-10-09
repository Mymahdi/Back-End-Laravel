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

    public function exportWeeklyBlogs(){
        dd(Auth::user());
            if (Auth::user()->role !== 'admin') {
                dd("admin confirmed");
                return response()->json(['error' => 'Unauthorized'], 403);
            }
    
            $latestFile = collect(Storage::disk('public')->files())
                ->filter(fn($file) => str_contains($file, 'weekly_blogs_'))
                ->sortByDesc(fn($file) => Storage::lastModified($file))
                ->first();
    
            if ($latestFile) {
                return Storage::disk('public')->download($latestFile);
            }
    
            return response()->json(['error' => 'No export file found'], 404);
        // }

        $startDate = now()->subWeek()->startOfWeek();
        $endDate = now()->subWeek()->endOfWeek();
        dump(now());
        dump($startDate);
        dd($endDate);

    }
}
