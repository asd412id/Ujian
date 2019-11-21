<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Support\Str;
use Validator;

class MapelController extends Controller
{

    public function index(Request $r)
    {
      $mapel = Mapel::when($r->cari,function($q,$role){
        $role = '%'.$role.'%';
        $q->where('kode','ilike',$role)
        ->orWhere('nama','ilike',$role);
      })
      ->orderBy('id','asc')
      ->paginate(10)->appends(request()->except('page'));
      $data = [
        'title' => 'Master Mata Pelajaran - Administrator',
        'breadcrumb' => 'Master Mata Pelajaran',
        'mapel' => $mapel
      ];
      return view("Admin::master.mapel.index",$data);
    }

    public function create(Request $r)
    {
      if ($r->ajax()) {
        return view("Admin::master.mapel.create");
      }
      return redirect()->route('admin.index');
    }

    public function store(Request $r)
    {
      $valid = Validator::make($r->all(),[
        'kode' => 'required|unique:mapel,kode',
        'nama' => 'required',
      ],[
        'kode.required' => 'Kode Mata Pelajaran tidak boleh kosong',
        'kode.unique' => 'Kode Mata Pelajaran :input telah digunakan',
        'nama.required' => 'Nama Mata Pelajaran tidak boleh kosong',
      ])->validate();

      $mapel = new Mapel;
      $mapel->uuid = (string) Str::uuid();
      $mapel->kode = $r->kode;
      $mapel->nama = $r->nama;

      if ($mapel->save()) {
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
          'data' => Mapel::where('uuid',$uuid)->first()
        ];
        return view("Admin::master.mapel.edit",$data);
      }
      return redirect()->route('admin.index');
    }
    public function update(Request $r, $uuid)
    {
      $valid = Validator::make($r->all(),[
        'kode' => 'required|unique:mapel,kode,'.$uuid.',uuid',
        'nama' => 'required',
      ],[
        'kode.required' => 'Kode Mata Pelajaran tidak boleh kosong',
        'kode.unique' => 'Kode Mata Pelajaran :input telah digunakan',
        'nama.required' => 'Nama Mata Pelajaran tidak boleh kosong',
      ])->validate();

      $mapel = Mapel::where('uuid',$uuid)->first();
      $mapel->kode = $r->kode;
      $mapel->nama = $r->nama;

      if ($mapel->save()) {
        return redirect()->back()->with('message', 'Data berhasil diubah');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function destroy($uuid)
    {
        $mapel = Mapel::where('uuid',$uuid)->first();
        if ($mapel->delete()) {
          return redirect()->back()->with('message', 'Data berhasil dihapus');
        }
        return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }
}
