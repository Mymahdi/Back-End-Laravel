<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\BlogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class ExportWeeklyBlogs extends Command
{
    protected $signature = 'export:weeklyblogs';
    protected $description = 'Exports blogs created in the previous week and stores them as an Excel file.';

    public function handle()
    {
        // $endDate = Carbon::now()->startOfWeek();
        // $startDate = $endDate->copy()->subWeek();
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subHour();
        $fileName = 'blogs_week_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d') . '.xlsx';
        Excel::store(new BlogsExport($startDate, $endDate), $fileName);
        $this->info('Weekly blogs export completed successfully and saved as ' . $fileName);
    }
}
