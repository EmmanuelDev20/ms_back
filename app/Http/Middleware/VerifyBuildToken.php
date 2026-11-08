<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * The public API is only meant for the Nuxt build on Netlify, which sends
 * "Authorization: Bearer <BUILD_API_TOKEN>". Everyone else gets 401.
 * Without a token configured, only the local environment is allowed through.
 */
class VerifyBuildToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = (string) config('services.build_api.token');

        if ($token === '') {
            abort_unless(app()->environment('local'), 403, 'BUILD_API_TOKEN is not configured.');

            return $next($request);
        }

        abort_unless(hash_equals($token, (string) $request->bearerToken()), 401);

        return $next($request);
    }
}
