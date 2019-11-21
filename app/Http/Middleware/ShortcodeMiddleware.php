<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Login;
use App\Models\Siswa;
use App\Models\Tes;

class ShortcodeMiddleware
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
        $response = $next($request);
        if (!method_exists($response,'content')) {
          return $response;
        }
        $response->setContent($this->shortcode($request->soal,$response->content()));
        return $response;
    }

    function shortcode($soal,$string){
        $index = 0;
         return preg_replace_callback('#\[(.*?)\]#', function ($matches) use($soal,&$index) {
             $whitespace_explode = explode(" ", $matches[1]);
             array_unshift($whitespace_explode,$soal,$index);
             $index++;
             $fnName = 'shortcode_'.$whitespace_explode[2];
             unset($whitespace_explode[2]);
             return method_exists($this,$fnName) ? call_user_func_array([$this,$fnName],$whitespace_explode) : $matches[0];
         }, $string);
     }

     function shortcode_gambar($soal,$index, $src="",$align="center",$width="",$height=""){
        switch ($align) {
          case 'kiri':
            $sa = 'left';
            break;
          case 'kanan':
            $sa = 'right';
            break;

          default:
            $sa = 'center';
            break;
        }

        if(filter_var($src, FILTER_VALIDATE_URL) === FALSE){
        	$src = url('uploads/'.$src);
        }

       return '<div class="text-'.$sa.'"><a href="'.$src.'" data-lightbox="'.$src.'"><img src="'.$src.'" alt="" style="max-width: 100%;" width="'.$width.'" height="'.$height.'" /></a></div>';
     }
     function shortcode_audio($soal,$index,$src="",$play=0,$align="center",$width="",$height=""){

       switch ($align) {
         case 'kiri':
           $sa = 'left';
           break;
         case 'kanan':
           $sa = 'right';
           break;

         default:
           $sa = 'center';
           break;
       }

       $scount = session()->get($soal.$src.$index.'count');

       $item = $src;

       if(filter_var($src, FILTER_VALIDATE_URL) === FALSE){
         $src = url('uploads/'.$src);
       }

       $content = '<div class="text-'.$sa.'" data-soal="'.$soal.'" data-item="'.$item.'" data-index="'.$index.'" data-count="'.$scount.'"><audio class="audio-play" controls controlsList="nodownload" width="'.$width.'" height="'.$height.'" style="max-width: 100%" data-play='.$play.' data-event="0"> <source src="'.$src.'" type="audio/mpeg"> Your browser does not support the audio element. </audio></div>';

       return $content;
     }
}
