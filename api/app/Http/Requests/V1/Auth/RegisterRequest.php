<?php

namespace App\Http\Requests\V1\Auth;

use App\Enums\Role;
use App\Models\User;
use App\Rules\Email;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roles = [
            Role::RIDER(),
            Role::CUSTOMER(),
        ];

        $unique = Rule::unique(User::class);
        $ifIsRider = Rule::requiredIf(fn() => $this->input('type') === Role::RIDER());
        $image = File::image()->min('1kb')->max('5mb');

        return [
            'type' => ['required', 'string', 'max:255', Rule::in($roles)],
            'drivers_license' => [$ifIsRider, $image],
            'vehicle_registration' => [$ifIsRider, $image],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', $unique],
            'email' => ['required', new Email, 'max:255', $unique],
            'password' => ['required', Password::default(), 'confirmed'],
        ];
    }
}
