<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string|max:255|min:2',
            'body' => 'nullable|string|max:1000|min:3', 
            'tags' => 'nullable|array',
            'tags.*' => 'string|min:2|max:32',
        ];
    }
}
