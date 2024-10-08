<?php

namespace App\Http\Resources;

use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'license' => $this->when($this->role === Role::RIDER && $this->relationLoaded('license'), fn() => $this->license),
            'registration' => $this->when($this->role === Role::RIDER && $this->relationLoaded('registration'), fn() => $this->registration),
        ];
    }
}
