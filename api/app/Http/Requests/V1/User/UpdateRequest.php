<?php

namespace App\Http\Requests\V1\User;

use App\Enums\Role;
use App\Models\User;
use App\Rules\Email;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
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
        $unique = $this->getUniqueRule();

        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', $unique],
            'email' => ['nullable', new Email, 'max:255', $unique],
            'password' => ['nullable', Password::default(), 'confirmed'],
            'role' => ['nullable', Rule::in(Role::values())],
            'approved' => ['nullable', 'boolean'],
            'blocked' => ['nullable', 'boolean'],
        ];
    }

    protected function getUniqueRule()
    {
        $rule = Rule::unique(User::class);
        $user = $this->user();

        if ($this->route('user')->getKey() === $user->getKey()) {
            return $rule->ignoreModel($user);
        }

        return $rule;
    }
}
