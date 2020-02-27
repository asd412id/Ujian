<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\ItemSoal;
use App\Models\JadwalUjian;
use App\Models\Tes;
use App\Models\Login;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Modules\Admin\Helper;
use Auth;

use GuzzleHttp\Client;
use Validator;
use PDF;

class JadwalUjianController extends Controller
{

    public function index(Request $r)
    {
      $jadwalUjian = JadwalUjian::when($r->cari,function($jadwal,$role){
        $role = '%'.$role.'%';
        $jadwal->where('nama_ujian','ilike',$role)
        ->orWhere('pin','ilike',$role)
        ->orWhereHas('login.tes.soalItem.getSoal.mapel',function($soal) use($jadwal,$role){
          $soal->where('nama','ilike',$role);
        })
        ->orWhereHas('login.siswa.kelas',function($kelas) use($jadwal,$role){
          $kelas->where('nama','ilike',$role);
        });
      })
      ->orderBy('aktif','desc')
      ->orderBy('updated_at','desc')
      ->paginate(30)->appends(request()->except('page'));
      $data = [
        'title' => 'Jadwal Ujian - Administrator',
        'breadcrumb' => 'Jadwal Ujian',
        'jadwal' => $jadwalUjian
      ];
      return view("Admin::master.jadwalujian.index",$data);
    }

    public function create(Request $r)
    {
      if ($r->ajax()) {
        $soal = Soal::all();
        return view("Admin::master.jadwalujian.create");
      }
      return redirect()->route('admin.index');
    }

    public function store(Request $r)
    {
      if ($r->ajax()) {
        $soal = Soal::where('kode',$r->kode_soal)->first();
        $valid = Validator::make($r->all(),[
          'soal' => 'required',
          'nama_ujian' => 'required',
          'peserta' => 'required',
          'mulai_ujian' => 'required',
          'selesai_ujian' => 'required',
          'jumlah_soal' => 'required|numeric|min:1',
          'bobot' => 'required|numeric|min:1',
          'lama_ujian' => 'required|numeric|min:1',
          'pin_digit' => 'required|numeric|min:1',
        ],[
          'soal.required' => 'Soal ujian tidak boleh kosong',
          'nama_ujian.required' => 'Nama ujian tidak boleh kosong',
          'peserta.required' => 'Peserta tidak boleh kosong',
          'mulai_ujian.required' => 'Waktu mulai ujian tidak boleh kosong',
          'mulai_ujian.date_format' => 'Format waktu mulai ujian tidak benar',
          'selesai_ujian.required' => 'Waktu selesai ujian tidak boleh kosong',
          'selesai_ujian.date_format' => 'Format waktu selesai ujian tidak benar',
          'jumlah_soal.required' => 'Jumlah soal tidak boleh kosong',
          'jumlah_soal.numeric' => 'Jumlah soal harus berupa angka',
          'jumlah_soal.min' => 'Jumlah soal tidak boleh kurang dari 1 menit',
          'bobot.required' => 'Bobot soal tidak boleh kosong',
          'bobot.numeric' => 'Bobot soal harus berupa angka',
          'bobot.min' => 'Bobot soal tidak boleh kurang dari 1 menit',
          'lama_ujian.required' => 'Lama ujian tidak boleh kosong',
          'lama_ujian.numeric' => 'Lama ujian harus berupa angka dalam menit',
          'lama_ujian.min' => 'Lama ujian tidak boleh kurang dari 1 menit',
          'pin_digit.required' => 'Jumlah digit pin tidak boleh kosong',
          'pin_digit.numeric' => 'Jumlah digit pin harus berupa angka',
          'pin_digit.min' => 'Jumlah digit pin tidak boleh kurang dari 1',
          ]);

          if ($valid->fails()) {
            $errs = $valid->errors()->all();
          }else{
            $errs = [];
            if ($r->mulai_ujian!=''&&$r->selesai_ujian!=''&&$r->lama_ujian!='') {
              $now = Carbon::parse(date('Y-m-d H:i'));
              $mulai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->mulai_ujian);
              $selesai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->selesai_ujian);
              if ($selesai_ujian < $now) {
                array_push($errs,'Waktu selesai ujian tidak boleh kurang dari waktu sekarang');
              }elseif ($mulai_ujian > $selesai_ujian) {
                array_push($errs,'Waktu selesai ujian tidak boleh kurang dari waktu mulai ujian');
              }elseif ($mulai_ujian->addMinutes($r->lama_ujian) > $selesai_ujian) {
                array_push($errs,'Lama ujian melebihi rentang waktu ujian');
              }
            }
            $jumlah_soal = ItemSoal::whereHas('getSoal',function($q) use($r){
              $q->whereIn('uuid',$r->soal);
            })
            ->count();
            if ($r->jumlah_soal > $jumlah_soal) {
              array_push($errs,'Jumlah soal tidak boleh melebihi soal ujian yang tersedia!');
            }
          }
          if (count($errs)) {
            return response()->json([
              'success'=>false,
              'messages'=>$errs
            ]);
          }
          return response()->json([
            'success'=>true
          ]);
      }

      $mulai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->mulai_ujian);
      $selesai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->selesai_ujian);

      $jadwalUjian = new JadwalUjian;
      $jadwalUjian->uuid = (string) Str::uuid();
      $jadwalUjian->nama_ujian = $r->nama_ujian;
      $jadwalUjian->soal = json_encode($r->soal);
      $jadwalUjian->peserta = json_encode($r->peserta);
      $jadwalUjian->mulai_ujian = $mulai_ujian->toDateTimeString();
      $jadwalUjian->selesai_ujian = $selesai_ujian->toDateTimeString();
      $jadwalUjian->jumlah_soal = $r->jumlah_soal;
      $jadwalUjian->jenis_soal = $r->jenis_soal;
      $jadwalUjian->bobot = $r->bobot;
      $jadwalUjian->lama_ujian = $r->lama_ujian;
      $jadwalUjian->acak_soal = $r->acak_soal;
      $jadwalUjian->pin = $this->generatePin($r->pin_digit);
      $jadwalUjian->tampil_nilai = $r->tampil_nilai;

      if ($jadwalUjian->save()) {
        return redirect()->back()->with('message', 'Data berhasil disimpan');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function generatePin($digit=4)
    {
        if ($digit<1) {
          $ditig = 4;
        }
        $pin = strtoupper(Str::random($digit));
        $cek = JadwalUjian::where('pin',$pin)->count();
        if ($cek) {
          return $this->generatePin($digit);
        }
        return $pin;
    }

    public function activate($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();
      if ($jadwal->aktif) {
        $jadwal->aktif = 0;
        $jadwal->login()->update(['end'=>Carbon::now()]);
      }else {
        if (Carbon::parse($jadwal->selesai_ujian) < Carbon::now()) {
          return redirect()->back()->withErrors('Jadwal tidak dapat diaktifkan karena waktu ujian telah berakhir pada '.date('d/m/Y H:i',strtotime($jadwal->selesai_ujian)));
        }
        $jadwal->aktif = 1;
        $jadwal->tes()->forceDelete();
      }
      if ($jadwal->save()) {
        return redirect()->back()->with('message', 'Jadwal ujian '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).' '.($jadwal->aktif==1?'diaktifkan':'dinonaktifkan'));
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }
    public function reset($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();
      if ($jadwal->login()->forceDelete()) {
        return redirect()->back()->with('message', 'Jadwal ujian telah direset');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function edit(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $jadwal = JadwalUjian::where('uuid',$uuid)->first();
        $soal = Soal::whereIn('uuid',json_decode($jadwal->soal))->get();
        $siswa = Siswa::whereIn('uuid',json_decode($jadwal->peserta))->orderBy('nama','asc')->get();
        return view("Admin::master.jadwalujian.edit",[
          'data'=>$jadwal,
          'soal'=>$soal,
          'siswa'=>$siswa
        ]);
      }
      return redirect()->route('admin.index');
    }

    public function update(Request $r, $uuid)
    {
      if ($r->ajax()) {
        $soal = Soal::where('kode',$r->kode_soal)->first();
        $valid = Validator::make($r->all(),[
          'soal' => 'required',
          'nama_ujian' => 'required',
          'peserta' => 'required',
          'mulai_ujian' => 'required',
          'selesai_ujian' => 'required',
          'jumlah_soal' => 'required|numeric|min:1',
          'bobot' => 'required|numeric|min:1',
          'lama_ujian' => 'required|numeric|min:1',
        ],[
          'soal.required' => 'Soal ujian tidak boleh kosong',
          'nama_ujian.required' => 'Nama ujian tidak boleh kosong',
          'peserta.required' => 'Peserta tidak boleh kosong',
          'mulai_ujian.required' => 'Waktu mulai ujian tidak boleh kosong',
          'mulai_ujian.date_format' => 'Format waktu mulai ujian tidak benar',
          'selesai_ujian.required' => 'Waktu selesai ujian tidak boleh kosong',
          'selesai_ujian.date_format' => 'Format waktu selesai ujian tidak benar',
          'jumlah_soal.required' => 'Jumlah soal tidak boleh kosong',
          'jumlah_soal.numeric' => 'Jumlah soal harus berupa angka',
          'jumlah_soal.min' => 'Jumlah soal tidak boleh kurang dari 1 menit',
          'bobot.required' => 'Bobot soal tidak boleh kosong',
          'bobot.numeric' => 'Bobot soal harus berupa angka',
          'bobot.min' => 'Bobot soal tidak boleh kurang dari 1 menit',
          'lama_ujian.required' => 'Lama ujian tidak boleh kosong',
          'lama_ujian.numeric' => 'Lama ujian harus berupa angka dalam menit',
          'lama_ujian.min' => 'Lama ujian tidak boleh kurang dari 1 menit',
          ]);

          if ($valid->fails()) {
            $errs = $valid->errors()->all();
          }else{
            $errs = [];
            if ($r->mulai_ujian!=''&&$r->selesai_ujian!=''&&$r->lama_ujian!='') {
              $now = Carbon::parse(date('Y-m-d H:i'));
              $mulai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->mulai_ujian);
              $selesai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->selesai_ujian);
              if ($selesai_ujian < $now) {
                array_push($errs,'Waktu selesai ujian tidak boleh kurang dari waktu sekarang');
              }elseif ($mulai_ujian > $selesai_ujian) {
                array_push($errs,'Waktu selesai ujian tidak boleh kurang dari waktu mulai ujian');
              }elseif ($mulai_ujian->addMinutes($r->lama_ujian) > $selesai_ujian) {
                array_push($errs,'Lama ujian melebihi rentang waktu ujian');
              }
            }
            $jumlah_soal = ItemSoal::whereHas('getSoal',function($q) use($r){
              $q->whereIn('uuid',$r->soal);
            })
            ->count();
            if ($r->jumlah_soal > $jumlah_soal) {
              array_push($errs,'Jumlah soal tidak boleh melebihi soal ujian yang tersedia!');
            }
          }
          if (count($errs)) {
            return response()->json([
              'success'=>false,
              'messages'=>$errs
            ]);
          }
          return response()->json([
            'success'=>true
          ]);
      }

      $mulai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->mulai_ujian);
      $selesai_ujian = Carbon::createFromFormat('d/m/Y - H:i',$r->selesai_ujian);

      $jadwalUjian = JadwalUjian::where('uuid',$uuid)->first();
      $jadwalUjian->nama_ujian = $r->nama_ujian;
      $jadwalUjian->soal = json_encode($r->soal);
      $jadwalUjian->peserta = json_encode($r->peserta);
      $jadwalUjian->mulai_ujian = $mulai_ujian->toDateTimeString();
      $jadwalUjian->selesai_ujian = $selesai_ujian->toDateTimeString();
      $jadwalUjian->jumlah_soal = $r->jumlah_soal;
      $jadwalUjian->jenis_soal = $r->jenis_soal;
      $jadwalUjian->bobot = $r->bobot;
      $jadwalUjian->lama_ujian = $r->lama_ujian;
      $jadwalUjian->acak_soal = $r->acak_soal;
      $jadwalUjian->tampil_nilai = $r->tampil_nilai;

      if ($jadwalUjian->save()) {
        return redirect()->back()->with('message', 'Data berhasil disimpan');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function destroy($uuid)
    {
        $jadwalUjian = JadwalUjian::where('uuid',$uuid)->first();
        if ($jadwalUjian->login) {
          $jadwalUjian->login()->forceDelete();
        }
        if ($jadwalUjian->tes) {
          $jadwalUjian->tes()->forceDelete();
        }
        Auth::guard('siswa')->logout();
        if ($jadwalUjian->forceDelete()) {
          return redirect()->back()->with('message', 'Data berhasil dihapus');
        }
        return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function monitoring(Request $r)
    {
      $jadwalUjian = JadwalUjian::when($r->cari,function($jadwal,$role){
        $role = '%'.$role.'%';
        $jadwal->where('nama_ujian','ilike',$role)
        ->orWhere('pin','ilike',$role)
        ->orWhereHas('login.tes.soalItem.getSoal.mapel',function($soal) use($jadwal,$role){
          $soal->where('nama','ilike',$role);
        })
        ->orWhereHas('login.siswa.kelas',function($kelas) use($jadwal,$role){
          $kelas->where('nama','ilike',$role);
        });
      })
      ->where('aktif',1)
      ->paginate(30)->appends(request()->except('page'));
      $data = [
        'title' => 'Monitoring Ujian - Administrator',
        'breadcrumb' => 'Monitoring Ujian',
        'jadwal' => $jadwalUjian
      ];
      return view("Admin::monitoring.index",$data);
    }

    public function monitoringDetail($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();
      $login = $jadwal->login()->withTrashed()
      ->orderBy('id','asc')
      ->get();

      $kelas = '';
      $mapel = '';

      $getKelas = Kelas::whereHas('siswa',function($q) use($jadwal){
        $q->whereIn('uuid',json_decode($jadwal->peserta));
      })
      ->orderBy('tingkat','asc')
      ->select('nama')
      ->get();

      if (count($getKelas)) {
        foreach ($getKelas as $key => $k) {
          $kelas .= $k->nama;
          if ($key < count($getKelas)-2) {
            $kelas .= ', ';
          }elseif ($key == count($getKelas)-2) {
            if (count($getKelas) > 2) {
              $kelas .= ',';
            }
            $kelas .= ' dan ';
          }
        }
      }

      $getMapel = Mapel::whereHas('soal',function($q) use($jadwal){
        $q->whereIn('uuid',json_decode($jadwal->soal));
      })
      ->orderBy('id','asc')
      ->select('nama')
      ->get();

      if (count($getMapel)) {
        foreach ($getMapel as $key => $m) {
          $mapel .= $m->nama;
          if ($key < count($getMapel)-2) {
            $mapel .= ', ';
          }elseif ($key == count($getMapel)-2) {
            if (count($getMapel) > 2) {
              $mapel .= ',';
            }
            $mapel .= ' dan ';
          }
        }
      }

      return view("Admin::monitoring.detail",[
        'jadwalUjian'=>$jadwal,
        'kelas'=>$kelas,
        'mapel'=>$mapel,
        'login'=>$login,
        'title' => 'Monitoring Ujian - Administrator',
        'breadcrumb' => 'Monitoring '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian),
      ]);
    }

    public function monitoringGetData(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $jadwal = JadwalUjian::where('uuid',$uuid)->first();
        $login = $jadwal->login()->whereNotNull('_token')
        ->orderBy('id','asc')
        ->get();
        return view("Admin::monitoring.getdata",[
          'data'=>$login,
          'jadwalUjian'=>$jadwal
        ]);
      }
      return redirect()->route('admin.index');
    }

    public function monitoringStop($pin,$noujian)
    {
      if (request()->ajax()) {
        Login::where('pin',$pin)
        ->where('noujian',$noujian)->update([
          'end'=>Carbon::now()->toDateTimeString()
        ]);
      }
      return redirect()->route('admin.index');
    }

    public function monitoringReset($pin,$noujian)
    {
      if (request()->ajax()) {
        $login = Login::where('pin',$pin)->where('noujian',$noujian)->first();
        $login->_token = null;
        $login->end = null;
        $login->ip_address = null;
        $login->created_at = Carbon::now()->toDateTimeString();
        $login->save();
      }
      return redirect()->route('admin.index');
    }

    public function printKartu($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();
      $kelas = Kelas::where('uuid',$uuid)->first();

      if ($jadwal) {
        $filename = 'Kartu Peserta Ujian '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).'.pdf';
        $peserta = Siswa::whereIn('uuid',json_decode($jadwal->peserta))->orderBy('id','asc')->get();
        if (!count($peserta)) {
          return redirect()->back()->withErrors('Data siswa tidak tersedia');
        }
      }elseif ($kelas) {
        if (!count($kelas->siswa)) {
          return redirect()->back()->withErrors('Data siswa tidak tersedia');
        }
        $filename = 'Kartu Peserta Ujian Kelas '.$kelas->nama.' '.$kelas->jurusan.'.pdf';
        $peserta = $kelas->siswa()->orderBy('id','asc')->get();
      }

      // $view = view('Admin::master.jadwalujian.kartu',[
      //   'jadwal'=>$jadwal,
      //   'peserta'=>$peserta,
      //   'title'=>$filename,
      //   'sekolah'=>Sekolah::first(),
      //   'helper'=>new Helper
      // ])->render();
      //
      // $client = new Client;
      // $res = $client->request('POST','http://docker.local:/pdf',[
      //   'form_params'=>[
      //     'html'=>str_replace(url('/'),'http://nginx_ujian/',$view),
      //     'options[page-width]'=>'21.5cm',
      //     'options[page-height]'=>'33cm',
      //     'options[margin-top]'=>'0.5cm',
      //     'options[margin-bottom]'=>'0',
      //     'options[margin-left]'=>'0',
      //     'options[margin-right]'=>'0',
      //   ]
      // ]);
      //
      // if ($res->getStatusCode() == 200) {
      //   return response()->attachment($res->getBody()->getContents(),$filename,'application/pdf');
      // }
      //
      // return redirect()->back()->withErrors(['Tidak dapat mendownload file! Silahkan hubungi operator']);

      $pdf = PDF::loadView('Admin::master.jadwalujian.kartu',[
        'jadwal'=>$jadwal,
        'peserta'=>$peserta,
        'title'=>str_replace('.pdf','',$filename),
        'sekolah'=>Sekolah::first(),
        'helper'=>new Helper
      ]);

      return $pdf->setOptions([
        'page-width'=>'21.5cm',
        'page-height'=>'33cm'
      ])
      ->setOption('margin-top','0.5cm')
      ->setOption('margin-bottom',0)
      ->setOption('margin-left',0)
      ->setOption('margin-right',0)
      ->stream($filename);
    }

    public function printAbsen($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();

      if ($jadwal) {
        $filename = 'Daftar Hadir Peserta Ujian '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).'.pdf';
        $peserta = Siswa::whereIn('uuid',json_decode($jadwal->peserta))->orderBy('id','asc')->get();
        if (!count($peserta)) {
          return redirect()->back()->withErrors('Data siswa tidak tersedia');
        }
      }else {
        return redirect()->back()->withErrors('Jadwal ujian tidak tersedia');
      }

      $kelas = '';
      $mapel = '';

      $getKelas = Kelas::whereHas('siswa',function($q) use($jadwal){
        $q->whereIn('uuid',json_decode($jadwal->peserta));
      })
      ->orderBy('tingkat','asc')
      ->select('nama')
      ->get();

      if (count($getKelas)) {
        foreach ($getKelas as $key => $k) {
          $kelas .= $k->nama;
          if ($key < count($getKelas)-2) {
            $kelas .= ', ';
          }elseif ($key == count($getKelas)-2) {
            if (count($getKelas) > 2) {
              $kelas .= ',';
            }
            $kelas .= ' dan ';
          }
        }
      }

      $getMapel = Mapel::whereHas('soal',function($q) use($jadwal){
        $q->whereIn('uuid',json_decode($jadwal->soal));
      })
      ->orderBy('id','asc')
      ->select('nama')
      ->get();

      if (count($getMapel)) {
        foreach ($getMapel as $key => $m) {
          $mapel .= $m->nama;
          if ($key < count($getMapel)-2) {
            $mapel .= ', ';
          }elseif ($key == count($getMapel)-2) {
            if (count($getMapel) > 2) {
              $mapel .= ',';
            }
            $mapel .= ' dan ';
          }
        }
      }

      $pdf = PDF::loadView('Admin::master.jadwalujian.daftar-hadir',[
        'jadwal'=>$jadwal,
        'peserta'=>$peserta,
        'kelas'=>$kelas,
        'mapel'=>$mapel,
        'title'=>str_replace('.pdf','',$filename),
        'sekolah'=>Sekolah::first(),
        'helper'=>new Helper
      ]);

      return $pdf->setOptions([
        'page-width'=>'21.5cm',
        'page-height'=>'33cm'
      ])
      ->stream($filename);
    }

    public function printBerita($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();

      if ($jadwal) {
        $filename = 'Berita Acara Pelaksanaan '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).'.pdf';
        $peserta = Siswa::whereIn('uuid',json_decode($jadwal->peserta))->orderBy('id','asc')->get();
        if (!count($peserta)) {
          return redirect()->back()->withErrors('Data siswa tidak tersedia');
        }
      }else {
        return redirect()->back()->withErrors('Jadwal ujian tidak tersedia');
      }

      $kelas = '';
      $mapel = '';

      $getKelas = Kelas::whereHas('siswa',function($q) use($jadwal){
        $q->whereIn('uuid',json_decode($jadwal->peserta));
      })
      ->orderBy('tingkat','asc')
      ->select('nama')
      ->get();

      if (count($getKelas)) {
        foreach ($getKelas as $key => $k) {
          $kelas .= $k->nama;
          if ($key < count($getKelas)-2) {
            $kelas .= ', ';
          }elseif ($key == count($getKelas)-2) {
            if (count($getKelas) > 2) {
              $kelas .= ',';
            }
            $kelas .= ' dan ';
          }
        }
      }

      $getMapel = Mapel::whereHas('soal',function($q) use($jadwal){
        $q->whereIn('uuid',json_decode($jadwal->soal));
      })
      ->orderBy('id','asc')
      ->select('nama')
      ->get();

      if (count($getMapel)) {
        foreach ($getMapel as $key => $m) {
          $mapel .= $m->nama;
          if ($key < count($getMapel)-2) {
            $mapel .= ', ';
          }elseif ($key == count($getMapel)-2) {
            if (count($getMapel) > 2) {
              $mapel .= ',';
            }
            $mapel .= ' dan ';
          }
        }
      }

      $pdf = PDF::loadView('Admin::master.jadwalujian.berita-acara',[
        'jadwal'=>$jadwal,
        'peserta'=>$peserta,
        'kelas'=>$kelas,
        'mapel'=>$mapel,
        'title'=>str_replace('.pdf','',$filename),
        'sekolah'=>Sekolah::first(),
        'helper'=>new Helper
      ]);

      return $pdf->setOptions([
        'page-width'=>'21.5cm',
        'page-height'=>'33cm'
      ])
      ->stream($filename);
    }

    public function getPeserta(Request $r)
    {
      if ($r->ajax()) {
        $result = [];
        $search = $r->term;
        $data = Siswa::whereHas('kelas',function($q) use($search){
          $q->where('kode',$search)
          ->orWhere('nama','ilike','%'.$search.'%')
          ->orWhere('tingkat',$search);
        })
        ->orWhere('nama','ilike','%'.$search.'%')
        ->select('uuid','noujian','kode_kelas','nama')
        ->with('kelas')
        ->orderBy('nama','asc')
        ->get();

        if (count($data)) {
          foreach ($data as $key => $p) {
            array_push($result,[
              'id'=>$p->uuid,
              'text'=>'('.$p->noujian.') '.$p->nama.' - Kelas '.($p->kelas->nama??'-').'/'.($p->kelas->jurusan??'-')
            ]);
          }
        }

        return response()->json($result);
      }
      return redirect()->route('admin.index');
    }

    public function getSoal(Request $r)
    {
      if ($r->ajax()) {
        $result = [];
        $search = $r->term;
        $data = Soal::whereHas('mapel',function($q) use($search){
          $q->where('nama','ilike','%'.$search.'%');
        })
        ->orWhere('nama','ilike','%'.$search.'%')
        ->orWhere('kode','ilike','%'.$search.'%')
        ->select('uuid','kode','nama')
        ->orderBy('nama','asc')
        ->get();

        if (count($data)) {
          foreach ($data as $key => $p) {
            array_push($result,[
              'id'=>$p->uuid,
              'text'=>'('.$p->kode.') - '.$p->nama.' -  Soal: '.$p->item()->count()
            ]);
          }
        }

        return response()->json($result);
      }
      return redirect()->route('admin.index');
    }
}
