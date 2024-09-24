<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\models\BlogModel;

class BlogsExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $blogs = BlogModel::getAllBlogs();
    
        return $blogs->map(function ($blog) {
            return [
                'id'           => $blog->id,
                'title'        => $blog->title,
                'body'         => $blog->body,
                'author_name'  => $blog->author_name,
                'num_likes'    => $blog->num_likes,
                'num_tags'     => $blog->num_tags,
                'user_id'      => $blog->user_id,
                'created_at'   => $blog->created_at,
                '',            //empty column for seperating
                '',            // empty column for seperating
                'updated_at'   => $blog->last_update
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'Id',
            'Title',
            'Body',
            'Author name',
            'Number of likes',
            'Number of tags',
            'User_id',
            'Created_at',
            '',
            '',
            'Last_update'
        ];
    }
}
