<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'max:30', 'alpha_dash',
                Rule::unique(User::class, 'username')->ignore($this->user()->id),
            ],
            // Email is optional and non-unique
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
        ];
    }
}
