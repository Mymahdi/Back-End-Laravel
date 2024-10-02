<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:1000', 
            'publish_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'string|min:2|max:32',
        ];
    }
}
