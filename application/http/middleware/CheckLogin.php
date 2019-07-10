<?php

namespace app\http\middleware;

class CheckLogin
{
    public function handle($request, \Closure $next)
    {

    	if (!session('userid')) {
            return redirect(url("/admin/login"));
        }
        return $next($request);
    }
}
 