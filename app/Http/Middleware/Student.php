<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Student
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
        if(!Auth::check()){
            return redirect()->route('login');
        }
        if(Auth::user()->role == 'admin')
        {
            return redirect()->route('admin');
        }
        if(Auth::user()->role == 'teacher')
        {
            return redirect()->route('teacher');
        }
        if(Auth::user()->role == 'student')
        {
            return $next($request);
        }
    }
}
