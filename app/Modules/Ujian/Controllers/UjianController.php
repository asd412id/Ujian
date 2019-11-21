<?php

namespace App\Modules\Ujian\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\JadwalUjian;
use App\Models\Login;
use App\Models\ItemSoal;
use App\Models\Tes;
use Illuminate\Support\Str;
use Validator;
use Auth;
use Carbon\Carbon;

class UjianController extends Controller
{

    public function index()
    {
      return view("Ujian::index",[
        'sekolah'=>Sekolah::first()
      ]);
    }

    public function login(Request $r)
    {
      Validator::make($r->all(),[
        'noujian'=>'required',
        'password'=>'required',
        'pin'=>'required',
      ],[
        'noujian.required'=>'No ujian tidak boleh kosong',
        'password.required'=>'Password tidak boleh kosong',
        'pin.required'=>'Pin harus diisi',
      ])->validate();

      if (Auth::guard('siswa')->attempt([
        'noujian'=>$r->noujian,
        'password'=>$r->password
      ],1)) {
        $cekPin = JadwalUjian::where('pin',$r->pin)->where('aktif',1);
        if (!$cekPin->count()) {
          Auth::guard('siswa')->logout();
          return redirect()->route('ujian.login')->withErrors(['PIN sesi tidak ditemukan'])->withInput($r->only('noujian'));
        }elseif (Carbon::now() <= Carbon::parse($cekPin->first()->mulai_ujian)) {
          Auth::guard('siswa')->logout();
          return redirect()->route('ujian.login')->withErrors(['Sesi ujian akan dimulai pada tanggal '.date('d/m/Y',strtotime($cekPin->first()->mulai_ujian)).' pukul '.date('H:i',strtotime($cekPin->first()->mulai_ujian))])->withInput($r->only('noujian'));
        }elseif (Carbon::now() >= Carbon::parse($cekPin->first()->selesai_ujian)) {
          Auth::guard('siswa')->logout();
          return redirect()->route('ujian.login')->withErrors(['Sesi ujian telah berakhir'])->withInput($r->only('noujian'));
        }elseif (Auth::guard('siswa')->user()->attemptLogin()->where('pin',$r->pin)->count()) {
          Auth::guard('siswa')->logout();
          return redirect()->route('ujian.login')->withErrors(['Anda sudah selesai ujian'])->withInput($r->only('noujian'));
        }elseif (Auth::guard('siswa')->user()->kode_kelas != $cekPin->first()->kode_kelas && $cekPin->first()->kode_kelas != 'all') {
          Auth::guard('siswa')->logout();
          return redirect()->route('ujian.login')->withErrors(['Anda tidak dapat mengikuti ujian ini'])->withInput($r->only('noujian'));
        }
        $user = Auth::guard('siswa')->user();
        $ujian = new Login;
        $ujian->uuid = (string)Str::uuid();
        $ujian->noujian = $user->noujian;
        $ujian->_token = $user->remember_token;
        $ujian->pin = $r->pin;
        $ujian->start = null;
        $ujian->end = null;
        $ujian->ip_address = $r->ip();
        $ujian->save();
        session()->flush();
        return redirect()->back();
      }

      return redirect()->back()->withErrors(['Data login tidak benar!'])->withInput($r->only('noujian','pin'));
    }

    public function cekData()
    {
      return view('Ujian::cekdata',[
        'title'=>'Pemeriksaan Data Ujian',
        'breadcrumb'=>'Pemeriksaan Data Ujian',
        'siswa'=>Auth::guard('siswa')->user()
      ]);
    }

    public function timer($int=false)
    {
      $login = Auth::guard('siswa')->user()->login;
      $jadwal = $login->jadwal;
      $timerNow = Carbon::now()->addMinutes($jadwal->lama_ujian) <= Carbon::parse($jadwal->selesai_ujian) ? Carbon::now()->addMinutes($jadwal->lama_ujian) : Carbon::parse($jadwal->selesai_ujian);

      $intval = $timerNow->diffInSeconds(Carbon::parse($login->start)->addMinutes($jadwal->lama_ujian));

      if ($int) {
        return $intval;
      }

      return $timerNow->subSeconds($intval);
    }

    public function tes()
    {
      $login = Auth::guard('siswa')->user()->login;
      if ($login->start==null) {
        $login->start = Carbon::now()->toDateTimeString();
        $SP = $login->jadwal->getSoal->item()->where('jenis_soal','P')->select('uuid')->get()->pluck('uuid')->toArray();
        $SE = $login->jadwal->getSoal->item()->where('jenis_soal','E')->select('uuid')->get()->pluck('uuid')->toArray();
        @shuffle($SP);
        @shuffle($SE);
        $soal = @array_merge($SP,$SE);
        $login->soal_ujian = json_encode($soal);
        $login->save();
      }

      $timer = $this->timer();

      return view('Ujian::tes',[
        'title'=>'Mulai Ujian '.$login->jadwal->getSoal->nama,
        'breadcrumb'=>'Ujian '.$login->jadwal->getSoal->nama,
        'soal'=>$login->soal_ujian,
        'now'=>Carbon::now(),
        'timer'=>$timer,
        'siswa'=>Auth::guard('siswa')->user()
      ]);
    }

    public function getsoal(Request $r)
    {
      if ($r->ajax()) {
        $siswa = Auth::guard('siswa')->user();

        if (!$siswa || !$siswa->login || @$siswa->login->end) {
          return $err = 0;
        }

        if ($r->has('checking')) {
          sleep(3);
          return response()->json([
            'status' => 1,
            'token' => csrf_token()
          ]);
        }

        $soal = ItemSoal::where('uuid',$r->soal)->first();

        $opsis = null;

        $cek = Tes::where('noujian',$siswa->noujian)
        ->where('pin',$siswa->login->pin)
        ->where('kode_soal',$soal->kode_soal)
        ->where('soal_item',$soal->uuid)
        ->first();

        if (!$cek) {
          if ($soal->jenis_soal=='P') {
            if ($soal->acak_opsi=='Y') {
              $keys = array_keys(json_decode($soal->opsi));
              shuffle($keys);
              foreach ($keys as $v) {
                $opsis[$v] = json_decode($soal->opsi)[$v];
              }
            }else {
              $opsis = json_decode($soal->opsi);
            }
          }

          $tes = new Tes;
          $tes->noujian = $siswa->noujian;
          $tes->pin = $siswa->login->pin;
          $tes->kode_soal = $soal->kode_soal;
          $tes->soal_item = $soal->uuid;
          $tes->opsi = json_encode($opsis);

          $tes->save();

        }else {
          $opsis = json_decode($cek->opsi);
        }

        return view('Ujian::soal',[
          'soal'=>$soal,
          'siswa'=>$siswa,
          'allSoal'=>$siswa->login->soal_ujian,
          'opsis'=>$opsis,
          'key'=>$r->key
        ]);
      }
      return redirect()->route('ujian.login');
    }

    public function submit(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $siswa = Auth::guard('siswa')->user();
        $soal = ItemSoal::where('uuid',$uuid)->first();
        $tes = Tes::where('noujian',$siswa->noujian)
        ->where('pin',$siswa->login->pin)
        ->where('kode_soal',$soal->kode_soal)
        ->where('soal_item',$soal->uuid)
        ->first();

        $tes->jawaban = $r->jawab;
        if ($tes->save()) {
          return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false]);
      }
      return redirect()->route('ujian.login');
    }

    public function selesai()
    {
      $siswa = Auth::guard('siswa')->user();
      $siswa->login()->update([
        'end'=>Carbon::now()->toDateTimeString()
      ]);
      return redirect()->route('ujian.nilai');
    }

    public function nilai()
    {
      $siswa = Auth::guard('siswa')->user();
      $nilai = null;
      $nbenar = null;
      $soal = $siswa->login->jadwal->getSoal;
      $bobot = $soal->bobot;
      $jumlah_soal = $soal->item()->where('jenis_soal','P')->count();
      $dtes = Tes::where('noujian',$siswa->noujian)
      ->where('pin',$siswa->login->pin)->get();
      foreach ($dtes as $key => $tes) {
        $benar = @$soal->item()->where('uuid',$tes->soal_item)->first()->benar;
        if (!is_null($benar) && (string) $tes->jawaban == (string) $benar && $tes->soalItem->jenis_soal=='P') {
          if (is_null($nbenar)) {
            $nbenar = 0;
          }
          $nbenar++;
        }
      }
      if ($jumlah_soal) {
        $nilai = 0;
      }
      if (!is_null($nbenar)) {
        $nilai += round($nbenar/$jumlah_soal*$bobot,2);
      }
      return view('Ujian::nilai',[
        'title'=>'Hasil Ujian',
        'breadcrumb'=>'Hasil Ujian',
        'siswa'=>$siswa,
        'nilai'=>$nilai
      ]);
    }

    public function audioRepeat(Request $r)
    {
      if ($r->ajax()) {
        $soal = $r->soal;
        $audio = $r->audio;
        $count = $r->count;
        $scount = $soal.$audio.'count';

        session()->put([$scount=>$r->count]);
        return session()->get($scount);
      }
      return redirect()->route('ujian.login');
    }

    public function logout()
    {
      $siswa = Auth::guard('siswa');
      if ($siswa->user()) {
        $siswa->user()->login()->delete();
        $siswa->logout();
        session()->flush();
      }
      return redirect()->route('ujian.login');
    }

}
