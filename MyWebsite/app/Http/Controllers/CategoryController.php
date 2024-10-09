<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = DB::table('your_blog_table') 
            ->select('category_name', 'title', 'view_count')
            ->get();

        $categoriesCollection = collect($categories);

        $formattedData = $categoriesCollection->groupBy('category_name')->map(function ($group) {
            return $group->map(function ($item) {
                return [$item->title => $item->view_count];
            });
        });

        return response()->json($formattedData);
    }
}
