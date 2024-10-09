<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    public function getCategories(): JsonResponse
    {
        $apiLink = "https://api.sokanacademy.com/api/announcements/blog-index-header";

        $response = Http::get($apiLink);
        $blogs = collect($response->json('data'));
        $grouped = $blogs->groupBy(function ($item) {
            return $item['all']['category_name'];
        })
        ->map(function ($group) {
            return $group->map(function ($item) {
                return [
                    $item['all']['title'] => $item['all']['views_count']
                ];
            })->values();
        });

            return response()->json($grouped);
    }
}
