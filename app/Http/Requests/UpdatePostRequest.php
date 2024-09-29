<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'title' => [
        'nullable',
        'string',
        'min:1',
        'max:255',
        'required_without_all:content',
      ],
      'content' => [
        'nullable',
        'string',
        'min:1',
        'max:10000',
        'required_without_all:title',
      ],
    ];
  }

  public function messages()
  {
    return [
      'title.required_without_all' => 'Either title or content is required.',
      'content.required_without_all' => 'Either title or content is required.',
    ];
  }
}
