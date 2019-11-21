<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Support\Str;
use Validator;

class KelasController extends Controller
{

    public function index(Request $r)
    {
      $kelas = Kelas::when($r->cari,function($q,$role){
        $role = '%'.$role.'%';
        $q->where('kode','ilike',$role)
        ->orWhere('nama','ilike',$role)
        ->orWhere('jurusan','ilike',$role)
        ->orWhere('tingkat','ilike',$role);
      })
      ->orderBy('id','asc')
      ->paginate(10)->appends(request()->except('page'));
      $data = [
        'title' => 'Master Kelas - Administrator',
        'breadcrumb' => 'Master Kelas',
        'kelas' => $kelas
      ];
      return view("Admin::master.kelas.index",$data);
    }

    public function create(Request $r)
    {
      if ($r->ajax()) {
        return view("Admin::master.kelas.create");
      }
      return redirect()->route('admin.index');
    }

    public function store(Request $r)
    {
      $valid = Validator::make($r->all(),[
        'kode' => 'required|unique:kelas,kode',
        'nama' => 'required',
        'tingkat' => 'required',
      ],[
        'kode.required' => 'Kode Kelas tidak boleh kosong',
        'kode.unique' => 'Kode Kelas :input telah digunakan',
        'nama.required' => 'Nama Kelas tidak boleh kosong',
        'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
      ])->validate();

      $kelas = new Kelas;
      $kelas->uuid = (string) Str::uuid();
      $kelas->kode = $r->kode;
      $kelas->nama = $r->nama;
      $kelas->jurusan = $r->jurusan;
      $kelas->tingkat = $r->tingkat;

      if ($kelas->save()) {
        return redirect()->back()->with('message', 'Data berhasil disimpan');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $data = [
          'data' => Kelas::where('uuid',$uuid)->first()
        ];
        return view("Admin::master.kelas.edit",$data);
      }
      return redirect()->route('admin.index');
    }
    public function update(Request $r, $uuid)
    {
      $valid = Validator::make($r->all(),[
        'kode' => 'required|unique:kelas,kode,'.$uuid.',uuid',
        'nama' => 'required',
        'tingkat' => 'required',
      ],[
        'kode.required' => 'Kode Kelas tidak boleh kosong',
        'kode.unique' => 'Kode Kelas :input telah digunakan',
        'nama.required' => 'Nama Kelas tidak boleh kosong',
        'tingkat.required' => 'Tingkat Kelas tidak boleh kosong',
      ])->validate();

      $kelas = Kelas::where('uuid',$uuid)->first();
      $kelas->kode = $r->kode;
      $kelas->nama = $r->nama;
      $kelas->jurusan = $r->jurusan;
      $kelas->tingkat = $r->tingkat;

      if ($kelas->save()) {
        return redirect()->back()->with('message', 'Data berhasil diubah');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function destroy($uuid)
    {
        $kelas = Kelas::where('uuid',$uuid)->first();
        if ($kelas->delete()) {
          return redirect()->back()->with('message', 'Data berhasil dihapus');
        }
        return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }
}
