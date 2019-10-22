<?php

namespace App\Http\Middleware;

use Closure;

class SiteMiddleware
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
        $isMaintain = env('IS_MAINTAIN', 0);

        if ($isMaintain == 1) {
            return redirect('/maintain');
        } else {
            return $next($request);
        }
    }
}
