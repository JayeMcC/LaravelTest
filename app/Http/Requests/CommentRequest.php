<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorize the request
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Comment content is required.',
            'content.max' => 'Comment content must not exceed 500 characters.',
        ];
    }
}
