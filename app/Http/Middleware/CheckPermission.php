<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            abort(403);
        }

        $hasPermission = $user->role
            ->permissions
            ->contains('key', $permission);

        if (!$hasPermission) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
