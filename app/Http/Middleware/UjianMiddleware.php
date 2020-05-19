<?php

namespace App\Http\Middleware;

use App\Modules\Ujian\Controllers\UjianController as Ujian;
use Closure;
use Auth;
use Carbon\Carbon;

class UjianMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard=null)
    {
      $siswa = Auth::guard($guard)->user();
      $ujian = new Ujian;
      if ($siswa->login->end) {
        return redirect()->route('ujian.nilai');
      }elseif(@$siswa->login && !$ujian->timer()->diffInSeconds(Carbon::now(),false)){
        return redirect()->route('ujian.selesai');
      }
      return $next($request);
    }
}
