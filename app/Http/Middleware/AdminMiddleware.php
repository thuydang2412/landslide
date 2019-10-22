<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
//        echo "<pre>";
//        var_dump($request->route()->getActionName());
//        echo "</pre>";die;

        $actionName = $request->route()->getActionName();
        if (strpos($actionName, '@login')) {
            return $next($request);
        }

        $userId = session('user_id');
        if (empty($userId)) {
            return redirect('admin/login');
        } else {
            return $next($request);
        }
    }
}
