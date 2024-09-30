<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\Auth\InvalidPasswordException;
use App\Exceptions\Auth\UserMissingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function __construct(protected JWTGuard $guard)
    {
        //
    }

    public function check(Request $request)
    {
        $user = $request->user();

        return UserResource::make($user);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $id = $data['identifier'];

        /**
         * @var User|null
         */
        $user = User::whereUsername($id)
            ->orWhere('email', $id)
            ->first();

        if (!$user) {
            throw new UserMissingException(['identifier' => $id]);
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new InvalidPasswordException;
        }

        $token = $this->guard->login($user);

        return UserResource::make($user)->additional([
            'access' => [
                'type' => 'Bearer',
                'token' => $token,
                'expiry' => $this->guard->getTTL(),
            ],
        ]);
    }

    public function logout()
    {
        $this->guard->logout(true);

        return response()->noContent();
    }
}
