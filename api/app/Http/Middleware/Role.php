<?php

namespace App\Http\Middleware;

use App\Enums;
use App\Exceptions\Auth\UnauthorizedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if ($request->user()?->role !== Enums\Role::from($role)) {
            throw new UnauthorizedException;
        }

        return $next($request);
    }
}
