<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $apiLink = "https://api.sokanacademy.comss/api/announcements/blog-index-header";
        $response = Http::get($apiLink);
        $blogs = collect($response->json('data'));
    }
}
