<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
    ];
  }

  /**
   * Get the custom messages for validator errors.
   *
   * @return array
   */
  public function messages(): array
  {
    return [
      'name.required' => 'A name is required.',
      'email.required' => 'An email address is required.',
      'email.unique' => 'This email is already taken.',
      'password.required' => 'A password is required.',
      'password.confirmed' => 'Password confirmation does not match.',
    ];
  }
}
