<?php

namespace App\Http\Middleware;

use App\Permission;
use App\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::check()) {
            if (!$this->checkAccounts() && $request->segment(1) != 'settings' && $request->segment(1) != 'logout') {
                return redirect('settings')->with('error', 'Please add freelancer account credentials.');
            }
            if ($this->checkPermission($request)) {
                return $next($request);
            } else return redirect()->back()->with('error', 'Permission Denied');
        } else return redirect('login');
    }

    private function checkAccounts()
    {
        return DB::table('freelancer_accounts')->exists();
    }

    private function checkPermission($request)
    {

        if (Auth::user()->role_id == ADMIN || $request->is('logout')) {
            return true;
        } else {
            $method = $request->method();
            return RolePermission::whereHas('permission', function ($q) use ($request) {
                $q->whereModule($request->segment(1));
            })->whereRoleId(Auth::User()->role_id)->whereActionId($method == 'GET' ? 1 : 2)->exists();
        }
    }
}
