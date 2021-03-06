<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            switch ($guard) {
              case 'siswa':
                if (@Auth::guard($guard)->user()->login->end) {
                  return redirect()->route('ujian.nilai');
                }elseif (@Auth::guard($guard)->user()->login->start) {
                  return redirect()->route('ujian.tes');
                }
                return redirect()->route('ujian.cekdata');
                break;
              case 'admin':
                return redirect()->route('admin.index');
                break;
            }
        }

        return $next($request);
    }
}
