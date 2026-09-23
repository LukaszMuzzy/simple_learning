<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || (!$user->is_admin && !$user->is_teacher)) {
            abort(403, 'Access denied. Teacher or Admin privileges required.');
        }

        return $next($request);
    }
}
