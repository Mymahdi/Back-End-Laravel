<?php

namespace App\Http\Controllers;

use App\Exports\BlogsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function exportBlogs()
    {
        return Excel::download(new BlogsExport, 'Blogs.xlsx');
    }
}
