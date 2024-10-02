<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'body' => 'required|string|max:1000|min:5',
            'tags' => 'nullable|array|max:255',
            'tags.*' => 'string|min:2|max:32',
            'publish_at' => [
                'nullable',
                'date',
                'after_or_equal:now',
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.min' => 'The title must be at least 3 characters.',
            'body.required' => 'The body is required.',
            'body.max' => 'The body may not be greater than 1000 characters.',
            'body.min' => 'The body must be at least 5 characters.',
            'tags.array' => 'Tags should be an array.',
            'tags.*.string' => 'Each tag must be a string.',
            'tags.*.min' => 'Each tag must be at least 2 characters.',
            'tags.*.max' => 'Each tag may not be greater than 32 characters.',
            'publish_at.date' => 'Publish date must be a valid date.',
            'publish_at.after_or_equal' => 'The publish date cannot be before the current time.',
        ];
    }
}
