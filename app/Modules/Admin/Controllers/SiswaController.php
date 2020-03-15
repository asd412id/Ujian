<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Str;
use Validator;

class SiswaController extends Controller
{

    public function index(Request $r)
    {
      $siswa = Siswa::when($r->cari,function($q,$role){
        $s = [$role,'%'.$role.'%'];
        $q->where('noujian','like',$s[1])
        ->orWhere('nama','like',$s[1])
        ->orWhereHas('kelas',function($q) use($s){
          $q->where('kode',$s[0])
          ->orWhere('nama','like',$s[1])
          ->orWhere('nik','like',$s[1])
          ->orWhere('jurusan','like',$s[1])
          ->orWhere('tingkat','like',$s[1]);
        });
      })
      ->orderBy('id','asc')
      ->paginate(30)->appends(request()->except('page'));
      $data = [
        'title' => 'Master Siswa - Administrator',
        'breadcrumb' => 'Master Siswa',
        'siswa' => $siswa
      ];
      return view("Admin::master.siswa.index",$data);
    }

    public function create(Request $r)
    {
      if ($r->ajax()) {
        $data = [
          'kelas'=>Kelas::all()
        ];
        return view("Admin::master.siswa.create",$data);
      }
      return redirect()->route('admin.index');
    }

    public function store(Request $r)
    {
      $valid = Validator::make($r->all(),[
        'noujian' => 'required|alpha_dash|unique:siswa,noujian',
        'nama' => 'required',
        'kode_kelas' => 'required',
        'password' => 'required|min:4',
      ],[
        'noujian.required' => 'Nomor ujian tidak boleh kosong',
        'noujian.unique' => 'Nomor ujian :input telah digunakan',
        'noujian.alpha_dash' => 'Nomor ujian tidak boleh terdapat spasi',
        'nama.required' => 'Nama Siswa tidak boleh kosong',
        'kode_kelas.required' => 'Kelas tidak boleh kosong',
        'password.required' => 'Password tidak boleh kosong',
        'password.min' => 'Password tidak boleh kurang dari 4 digit',
      ])->validate();

      $siswa = new Siswa;
      $siswa->uuid = (string) Str::uuid();
      $siswa->noujian = $r->noujian;
      $siswa->nik = $r->nik;
      $siswa->nama = $r->nama;
      $siswa->kode_kelas = $r->kode_kelas;
      $siswa->password = bcrypt($r->password);
      $siswa->real_password = $r->password;
      $siswa->photo = $r->photo;

      if ($siswa->save()) {
        return redirect()->back()->with('message', 'Data berhasil disimpan');
      }
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $data = [
          'data' => Siswa::where('uuid',$uuid)->first(),
          'kelas'=>Kelas::all()
        ];
        return view("Admin::master.siswa.edit",$data);
      }
      return redirect()->route('admin.index');
    }
    public function update(Request $r, $uuid)
    {
      $valid = Validator::make($r->all(),[
        'noujian' => 'required|alpha_dash|unique:siswa,noujian,'.$uuid.',uuid',
        'nama' => 'required',
        'kode_kelas' => 'required',
      ],[
        'noujian.required' => 'Nomor ujian tidak boleh kosong',
        'noujian.unique' => 'Nomor ujian :input telah digunakan',
        'noujian.alpha_dash' => 'Nomor ujian tidak boleh terdapat spasi',
        'nama.required' => 'Nama Siswa tidak boleh kosong',
        'kode_kelas.required' => 'Kelas tidak boleh kosong',
      ])->validate();

      $siswa = Siswa::where('uuid',$uuid)->first();
      $siswa->noujian = $r->noujian;
      $siswa->nik = $r->nik;
      $siswa->nama = $r->nama;
      $siswa->kode_kelas = $r->kode_kelas;
      if ($r->password!='') {
        $siswa->password = bcrypt($r->password);
        $siswa->real_password = $r->password;
      }
      $siswa->photo = $r->photo;

      if ($siswa->save()) {
        return redirect()->back()->with('message', 'Data berhasil diubah');
      }
    }

    public function destroy($uuid)
    {
        $siswa = Siswa::where('uuid',$uuid)->first();
        if ($siswa->delete()) {
          return redirect()->back()->with('message', 'Data berhasil dihapus');
        }
    }
}
