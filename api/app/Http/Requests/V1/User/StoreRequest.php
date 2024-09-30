<?php

namespace App\Http\Requests\V1\User;

use App\Enums\Role;
use App\Models\User;
use App\Rules\Email;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === Role::ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $unique = Rule::unique(User::class);

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', $unique],
            'email' => ['required', new Email, 'max:255', $unique],
            'password' => ['required', Password::default(), 'confirmed'],
            'role' => ['required', Rule::in(Role::values())],
        ];
    }
}
