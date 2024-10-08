<?php

namespace App\Http\Controllers\V1;

use App\Enums\Role;
use App\Exceptions\Auth\InvalidPasswordException;
use App\Exceptions\Auth\UnauthorizedException;
use App\Exceptions\Auth\UserMissingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Processors\CustomerRegistration;
use App\Processors\RiderRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Illuminate\Support\Arr;

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

        if (!$user->approved) {
            throw new UnauthorizedException('Account is not approved', 'NOT_APPROVED');
        }

        if ($user->blocked) {
            throw new UnauthorizedException('Account is blocked', 'BLOCKED');
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

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $type = Role::from($data['type']);

        /**
         * @var \App\Interfaces\Processor|null
         */
        $processor = null;

        if($type === Role::RIDER) {
            $processor = app(RiderRegistration::class)
                ->withVehicleRegistration($data['registration'])
                ->withDriversLicense($data['license'])
                ->withData(Arr::except($data, ['type', 'registration', 'license']));
        }
        else if($type === Role::CUSTOMER) {
            $processor = app(CustomerRegistration::class)
                ->withData(Arr::except($data, ['type']));
        } else {
            throw new \Exception('Invalid type.');
        }

        $user = $processor->process();

        return UserResource::make($user);
    }
}
