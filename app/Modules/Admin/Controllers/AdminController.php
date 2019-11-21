<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Sekolah;
use App\Models\Users;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\ItemSoal;
use App\Models\JadwalUjian;
use App\Models\Login;
use App\Models\Tes;
use Validator;
use Auth;
use PDO;
use Artisan;

class AdminController extends Controller
{

    public function index()
    {
        // return view("Admin::index");
        return redirect()->route('sekolah.index');
    }

    public function login()
    {
      // try {
      //   DB::connection()->getPdo();
      // } catch (\Exception $e) {
      //   $pdo = new PDO(sprintf('mysql:host=%s;port=%d;', env('DB_HOST'), env('DB_PORT')), env('DB_USERNAME'), env('DB_PASSWORD'));
      //   $ok = $pdo->exec(sprintf(
      //       'CREATE DATABASE IF NOT EXISTS %s',
      //       env('DB_DATABASE',false)
      //   ));
      //   Artisan::call('migrate:fresh');
      //   Artisan::call('db:seed');
      // }

      return view("Admin::login",[
        'sekolah'=>Sekolah::first()
      ]);
    }

    public function dologin(Request $r)
    {
      Validator::make($r->all(),[
        'username'=>'required',
        'password'=>'required',
      ],[
        'username.required'=>'Username tidak boleh kosong',
        'password.required'=>'Password tidak boleh kosong',
      ])->validate();

      if (Auth::guard('admin')->attempt([
        'username'=>$r->username,
        'password'=>$r->password
      ],($r->remember?true:false))) {
        return redirect()->back();
      }

      return redirect()->back()->withErrors(['Data login tidak benar!'])->withInput($r->only('username','remember'));
    }

    public function logout()
    {
      $siswa = Auth::guard('admin')->logout();
      return redirect()->route('admin.login');
    }

    public function media()
    {
      $data = [
        'title'=>'Media - Administrator',
        'breadcrumb'=>'Media',
      ];
      return view('Admin::media',$data);
    }

    public function profile()
    {
      $admin = Auth::guard('admin')->user();
      return view('Admin::profile',[
        'title'=>'Edit Profil - Administrator',
        'breadcrumb'=>'Edit Profil',
        'data'=>$admin
      ]);
    }

    public function profileUpdate(Request $r)
    {
      $admin = Auth::guard('admin')->user();

      $rules = [
        'nama'=>'required',
        'username'=>'required|min:5|unique:users,username,'.$admin->uuid.',uuid',
        'password'=>'required|confirmed|min:5'
      ];

      $messages = [
        'nama.required'=>'Nama Admin harus diisi!',
        'username.required'=>'Username harus diisi!',
        'username.min'=>'Username tidak boleh kurang dari 5 karakter',
        'username.unique'=>'Username telah digunakan',
        'password.required'=>'Password harus diisi!',
        'password.confirmed'=>'Konfirmasi password tidak benar!',
        'password.min'=>'Password tidak boleh kurang dari 5 karakter!',
      ];

      Validator::make($r->all(),$rules,$messages)->validate();

      $update = Users::where('uuid',$admin->uuid)->first();
      $update->nama = $r->nama;
      $update->username = $r->username;
      $update->password = bcrypt($r->password);

      if ($update->save()) {
        Auth::guard('admin')->logout();
        return redirect()->back();
      }
    }

    public function downloadTemplate($type)
    {
      switch ($type) {
        case 'master':
          $template = base_path('templates/Master Data Template.xlsx');
          break;
        case 'soal':
          $template = base_path('templates/Soal Template.xlsx');
          break;

        default:
          return redirect()->back()->withErrors('Template tidak ditemukan');
          break;
      }

      return response()->download($template);
    }

    public function reset()
    {
      return view("Admin::reset",[
        'title'=>'Reset Data - Administrator',
        'breadcrumb'=>'Reset Data'
      ]);
    }

    public function resetData($data)
    {
      switch ($data) {
        case 'sekolah':
          $data = new Sekolah;
          @unlink(base_path('uploads/'.$data->first()->dept_logo));
          @unlink(base_path('uploads/'.$data->first()->logo));
          @unlink(base_path('thumbs/'.$data->first()->dept_logo));
          @unlink(base_path('thumbs/'.$data->first()->logo));
          $data->truncate();
          return redirect()->back()->with('message','Data Sekolah telah direset');
          break;
        case 'kelas':
          $data = new Kelas;
          $data->truncate();
          return redirect()->back()->with('message','Data Kelas telah direset');
          break;
        case 'mapel':
          $data = new Mapel;
          $data->truncate();
          return redirect()->back()->with('message','Data Mata Pelajaran telah direset');
          break;
        case 'siswa':
          $data = new Siswa;
          $data->truncate();
          return redirect()->back()->with('message','Data Siswa telah direset');
          break;
        case 'soal':
          $data = new Soal;
          ItemSoal::truncate();
          JadwalUjian::truncate();
          Tes::truncate();
          $data->truncate();
          return redirect()->back()->with('message','Data Soal telah direset');
          break;
        case 'jadwal':
          $data = new JadwalUjian;
          Login::truncate();
          Tes::truncate();
          $data->truncate();
          return redirect()->back()->with('message','Data jadwal telah direset');
          break;
        case 'media':
          $files = glob(base_path('uploads/*'));
          foreach($files as $file){
            if(is_file($file))
              unlink($file);
          }
          $files = glob(base_path('thumbs/*'));
          foreach($files as $file){
            if(is_file($file))
              unlink($file);
          }
          return redirect()->back()->with('message','Media telah dihapus');
          break;

        default:
          // code...
          break;
      }
    }

}
