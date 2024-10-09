<?php

// app/Exports/BlogsExport.php

namespace App\Exports;

use App\Models\Blog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BlogsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        return Blog::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->withCount('likes')
            ->with(['tags', 'author'])
            ->select('id', 'title', 'body', 'user_id');
    }

    public function headings(): array
    {
        return [
            'Blog ID',
            'Title',
            'Body',
            'Number of Likes',
            'Tags',
            'Author First Name',
            'Author Last Name',
            'Author Email',
        ];
    }

    public function map($blog): array
    {
        $likesCount = $blog->likes ? $blog->likes->count() : 0;
        return [
            $blog->id,
            $blog->title,
            $blog->body,
            $blog->likes->count() ?: "0",
            $blog->tags->pluck('name')->implode(', '),
            $blog->author->first_name,
            $blog->author->last_name,
            $blog->author->email,
        ];
    }
}
