<?php

namespace App\Http\Middleware;

use App\Models\Activation;
use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;

class CheckUserRoles
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if($user->hasRole('admin')){
                return $next($request);
            }

            $currentRoute = Route::currentRouteName();
            $roles = Role::pluck('slug');
            $roles = $roles->toArray();

            if ($user->hasRole(implode(',', $roles))) {
                $currentRole = Role::find($user->roles[0]->id);
                $role_permission = DB::table('permission_role')->where('role_id', $currentRole->id)->pluck('permission_id')->toArray();
                $permissions = Permission::whereIn('id', $role_permission)->pluck('slug')->toArray();

                if (in_array($currentRoute, $permissions)) {
                    return $next($request);
                } else {
                    return redirect()->route('public.home')->with('error', trans('Permission denied!'));
                }
            } else {
                return redirect()->route('public.home')->with('error', trans('Permission denied!'));
            }
        } else {
            return redirect()->route('login')->with('error', trans('You must login first!'));
        }
    }
}
