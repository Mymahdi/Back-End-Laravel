<?php


namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function tagsList(): JsonResponse
    {
        $tags = Tag::withCount('blogs')->get(['id', 'name', 'blogs_count']);

        $tagList = $tags->map(function ($tag) {
            return [
                'name' => $tag->name,
                'number' => $tag->blogs_count,
            ];
        });

        return response()->json($tagList);
    }
}
