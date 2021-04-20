<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roles) {
        $roles = explode('|', $roles);
        $count = 0;
        foreach ($roles as $role) {
            if (in_array($role, Auth::user()->getRoles())) {
                $count++;
            }
        }
        if ($count > 0) {
            return $next($request);
        } else {
            flash('error', 'Inefficient permissions');
            return redirect('/');
        }
    }
}
