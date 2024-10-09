<?php

namespace App\Exports;

use App\Models\Blog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class WeeklyBlogsExport implements FromQuery, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        return Blog::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select('title', 'body', 'author_name', 'num_likes', 'created_at');
    }

    public function headings(): array
    {
        return [
            'Id',
            'Title',
            'Body',
            'Author_id',
            'Author name',
            'Number of likes',
            // 'Number of tags',
            'Tags'
            'Created_at',
            '',
            '',
        ];
    }
}
