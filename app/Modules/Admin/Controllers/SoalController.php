<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\ItemSoal;
use Illuminate\Support\Str;
use App\Modules\Admin\Helper;
use Validator;

class SoalController extends Controller
{

  public function __construct()
  {
    $this->helper = new Helper;
  }

    public function index(Request $r)
    {
      $soal = Soal::when($r->cari,function($q,$role){
        $role = [$role,'%'.$role.'%'];
        $q->where('kode','ilike',$role[1])
        ->orWhere('nama','ilike',$role[1])
        ->orWhereHas('kelas',function($q) use($role){
          $q->where('kode',$role[0])
          ->orWhere('nama','ilike',$role[1])
          ->orWhere('tingkat','ilike',$role[1]);
        })
        ->orWhereHas('mapel',function($q) use($role){
          $q->where('kode',$role[0])
          ->orWhere('nama','ilike',$role[1]);
        });
      })
      ->orderBy('id','asc')
      ->paginate(30)->appends(request()->except('page'));
      $data = [
        'title' => 'Bank Soal - Administrator',
        'breadcrumb' => 'Bank Soal',
        'soal' => $soal
      ];
      return view("Admin::master.soal.index",$data);
    }

    public function create(Request $r)
    {
      if ($r->ajax()) {
        $data = [
          'mapel'=>Mapel::all()
        ];
        return view("Admin::master.soal.create",$data);
      }
      return redirect()->route('admin.index');
    }

    public function store(Request $r)
    {
      $valid = Validator::make($r->all(),[
        'kode' => 'required|alpha_dash|unique:soal,kode',
        'nama' => 'required',
        'kode_mapel' => 'required',
      ],[
        'kode.required' => 'Kode Soal tidak boleh kosong',
        'kode.unique' => 'Kode Soal :input telah digunakan',
        'kode.alpha_dash' => 'Kode Soal tidak boleh memiliki spasi',
        'nama.required' => 'Nama Soal tidak boleh kosong',
        'kode_mapel.required' => 'Mata pelajaran tidak boleh kosong',
      ])->validate();

      $soal = new Soal;
      $soal->uuid = (string) Str::uuid();
      $soal->kode = $r->kode;
      $soal->nama = $r->nama;
      $soal->kode_mapel = $r->kode_mapel;

      if ($soal->save()) {
        return redirect()->back()->with('message', 'Data berhasil disimpan');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function edit(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $data = [
          'mapel' => Mapel::all(),
          'data' => Soal::where('uuid',$uuid)->first()
        ];
        return view("Admin::master.soal.edit",$data);
      }
      return redirect()->route('admin.index');
    }
    public function update(Request $r, $uuid)
    {
      $valid = Validator::make($r->all(),[
        'kode' => 'required|alpha_dash|unique:soal,kode,'.$uuid.',uuid',
        'nama' => 'required',
        'kode_mapel' => 'required',
      ],[
        'kode.required' => 'Kode Soal tidak boleh kosong',
        'kode.unique' => 'Kode Soal :input telah digunakan',
        'kode.alpha_dash' => 'Kode Soal tidak boleh memiliki spasi',
        'nama.required' => 'Nama Soal tidak boleh kosong',
        'kode_mapel.required' => 'Mata pelajaran tidak boleh kosong',
      ])->validate();

      $soal = Soal::where('uuid',$uuid)->first();
      $soal->nama = $r->nama;
      $soal->kode_mapel = $r->kode_mapel;
      if ($soal->item) {
        $soal->item()->update(['kode_soal'=>$r->kode]);
      }
      if ($soal->tes) {
        $soal->tes()->update(['kode_soal'=>$r->kode]);
      }
      $soal->kode = $r->kode;

      if ($soal->save()) {
        return redirect()->back()->with('message', 'Data berhasil diubah');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function destroy($uuid)
    {
        $soal = Soal::where('uuid',$uuid)->first();
        if ($soal->item) {
          $soal->item()->forceDelete();
        }
        if ($soal->tes) {
          $soal->tes()->forceDelete();
        }
        if ($soal->delete()) {
          return redirect()->back()->with('message', 'Data berhasil dihapus');
        }
        return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function detail(Request $r,$uuid)
    {
      $soal= Soal::where('uuid',$uuid)->first();
      $soalItem = $soal->item()
      ->when($r->cari,function($q,$role){
        $role = [$role,'%'.$role.'%'];
        $q->where('soal','ilike',$role[1])
        ->orWhere('opsi','ilike',$role[1]);
        })
      ->orderBy('id','asc')
      ->paginate(30)->appends(request()->except('page'));
      $data = [
        'title' => 'Soal '.$soal->nama.' - Administrator',
        'breadcrumb' => 'Soal '.$soal->nama,
        'soal'=>$soal,
        'items'=>$soalItem,
        'helper'=>$this->helper
      ];
      return view('Admin::master.soal.items.index',$data);
    }

    public function itemCreate(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $data = [
          'uuid'=>$uuid
        ];
        return view("Admin::master.soal.items.create",$data);
      }
      return redirect()->route('admin.index');
    }

    public function itemStore(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $rules = [
          'jenis_soal'=>'required',
          'soal'=>'required'
        ];
        $messages = [
          'jenis_soal.required'=>'Jenis soal tidak boleh kosong',
          'soal.required'=>'Soal tidak boleh kosong'
        ];

        if ($r->jenis_soal=='P') {
          $rules['benar'] = 'required';
          foreach ($r->opsi as $key => $ops) {
            $rules['opsi.'.$key] = 'required';
            $messages['opsi.'.$key.'.required'] = 'Pilihan jawaban '.($key+1).' harus diisi';
          }
          $messages['benar.required'] = 'Jawaban benar harus dipilih';
        }
        $valid = Validator::make($r->all(),$rules,$messages);
        if ($valid->fails()) {
          return response()->json([
            'success'=>false,
            'messages'=>$valid->errors()->all()
          ]);
        }
        return response()->json([
          'success'=>true
        ]);
      }
      $soal= Soal::where('uuid',$uuid)->first();
      $item = new ItemSoal;
      $item->uuid = (string) Str::uuid();
      $item->kode_soal = $soal->kode;
      $item->jenis_soal = $r->jenis_soal;
      $item->soal = trim(strip_tags($r->soal,'<strong><b><em><i><br><span><u><strike><sup><sub>'));
      $item->acak_opsi = $r->jenis_soal=='P'?$r->acak_soal:null;
      $item->opsi = $r->jenis_soal=='P'?json_encode($r->opsi):null;
      $item->benar = $r->jenis_soal=='P'?$r->benar:null;

      if ($item->save()) {
        return redirect()->back()->with('message', 'Data berhasil disimpan');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function itemEdit(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $item = ItemSoal::where('uuid',$uuid)->first();
        $data = [
          'item'=>$item
        ];
        return view("Admin::master.soal.items.edit",$data);
      }
      return redirect()->route('admin.index');
    }

    public function itemUpdate(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $rules = [
          'jenis_soal'=>'required',
          'soal'=>'required'
        ];
        $messages = [
          'jenis_soal.required'=>'Jenis soal tidak boleh kosong',
          'soal.required'=>'Soal tidak boleh kosong'
        ];

        if ($r->jenis_soal=='P') {
          $rules['benar'] = 'required';
          // foreach ($r->opsi as $key => $ops) {
          //   $rules['opsi.'.$key] = 'required';
          //   $messages['opsi.'.$key.'.required'] = 'Pilihan jawaban '.($key+1).' harus diisi';
          // }
          $messages['benar.required'] = 'Jawaban benar harus dipilih';
        }
        $valid = Validator::make($r->all(),$rules,$messages);
        if ($valid->fails()) {
          return response()->json([
            'success'=>false,
            'messages'=>$valid->errors()->all()
          ]);
        }
        return response()->json([
          'success'=>true
        ]);
      }
      $item = ItemSoal::where('uuid',$uuid)->first();
      $item->jenis_soal = $r->jenis_soal;
      $item->soal = trim(strip_tags($r->soal,'<strong><b><em><i><br><span><u><strike><sup><sub>'));
      $item->acak_opsi = $r->jenis_soal=='P'?$r->acak_soal:null;
      $item->opsi = $r->jenis_soal=='P'?json_encode($r->opsi):null;
      $item->benar = $r->jenis_soal=='P'?$r->benar:null;

      if ($item->save()) {
        return redirect()->back()->with('message', 'Data berhasil diubah');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function itemDestroy($uuid)
    {
        $soal = ItemSoal::where('uuid',$uuid)->first();
        if ($soal->tes) {
          $soal->tes()->forceDelete();
        }
        if ($soal->delete()) {
          return redirect()->back()->with('message', 'Data berhasil dihapus');
        }
        return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function itemShow(Request $r,$uuid)
    {
      if ($r->ajax()) {
        $item = ItemSoal::where('uuid',$uuid)->first();
        $data = [
          'item'=>$item
        ];
        return view("Admin::master.soal.items.show",$data);
      }
      return redirect()->route('admin.index');
    }
}
