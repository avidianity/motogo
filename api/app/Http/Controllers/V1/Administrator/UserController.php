<?php

namespace App\Http\Controllers\V1\Administrator;

use App\Exceptions\User\UnableToDeleteException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\StoreRequest;
use App\Http\Requests\V1\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show(User $user)
    {
        return UserResource::make($user);
    }

    public function store(StoreRequest $request)
    {
        $user = new User($request->validated());
        $user->email_verified_at = now();
        $user->save();

        return UserResource::make($user);
    }

    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return UserResource::make($user);
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->getKey() === $user->getKey()) {
            throw new UnableToDeleteException('Cannot delete own account');
        }

        return DB::transaction(function () use ($user) {
            try {
                $user->load([
                    'license.file',
                    'registration.file',
                ]);

                $user->license?->file?->delete();
                $user->registration?->file?->delete();

                $user->delete();

                return response()->noContent();
            } catch (Exception $exception) {
                throw new UnableToDeleteException($exception->getMessage(), $exception);
            }
        });
    }
}
