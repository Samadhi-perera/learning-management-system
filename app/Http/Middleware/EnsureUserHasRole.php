<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            // Redirect user to their own role dashboard if trying to access unauthorized area
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Access denied to that section.'),
                'lecturer' => redirect()->route('lecturer.dashboard')->with('error', 'Access denied to that section.'),
                default => redirect()->route('student.dashboard')->with('error', 'Access denied to that section.'),
            };
        }

        return $next($request);
    }
}
