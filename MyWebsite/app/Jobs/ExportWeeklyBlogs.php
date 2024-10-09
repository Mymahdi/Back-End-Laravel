<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BlogsExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ExportWeeklyBlogs implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $startDate = Carbon::now()->subWeek()->startOfWeek();
        $endDate = Carbon::now()->subWeek()->endOfWeek();

        $fileName = 'weekly_blogs_' . now()->format('Y-m-d') . '.xlsx';

        Excel::store(new BlogsExport($startDate, $endDate), $fileName, 'public');
    }
}
