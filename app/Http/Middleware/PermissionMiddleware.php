<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\HasPermission;
class PermissionMiddleware
{
    use HasPermission;

    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if(!$user){
            return redirect('/login');
        }

        if($user->hasRole('admin')){
            return $next($request);
        } else {
            return redirect('/home');
        }

        return $next($request);
    }
}
