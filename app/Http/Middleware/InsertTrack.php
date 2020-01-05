<?php

namespace App\Http\Middleware;

use Closure;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Users;

class InsertTrack
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
        try{
            //insert track user
            (new Users(null,null))->createTrack();
            return $next($request);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
