<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Modules\Admin\Helper;

use App\Modules\Admin\Controllers\ImportController as Import;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use Validator;

class SekolahController extends Controller
{

    public function index()
    {
      $sekolah = Sekolah::first();
      $helper = new Helper;
      $ipv4 = $helper->getServerIP();

      $data = [
        'title' => 'Sekolah - Administrator',
        'breadcrumb' => 'Sekolah',
        'sekolah' => $sekolah,
        'ipv4' => $ipv4
      ];
      return view("Admin::sekolah.index",$data);
    }

    public function store(Request $r)
    {
      $valid = Validator::make($r->all(),[
        'kode' => 'required',
        'nama' => 'required',
      ],[
        'kode.required' => 'Kode Sekolah tidak boleh kosong',
        'nama.required' => 'Nama Sekolah tidak boleh kosong',
      ])->validate();

      if (Sekolah::first()) {
        $sekolah = Sekolah::first();
      }else {
        $sekolah = new Sekolah;
      }

      $sekolah->uuid = (string) Str::uuid();
      $sekolah->kode = $r->kode;
      $sekolah->nama = $r->nama;
      $sekolah->alamat = $r->alamat;
      $sekolah->kop_kartu = $r->kop_kartu;
      $sekolah->kop_type = $r->kop_type;

      if ($sekolah->save()) {
        return redirect()->back()->with('message', 'Data berhasil diubah');
      }
      return redirect()->back()->withErrors('Terjadi Kesalahan!');
    }

    public function uploadLogo(Request $r,$type)
    {
      if ($r->file('logo')->isValid()) {
        $ext = ['jpg','jpeg','png','gif'];
        $sekolah = Sekolah::first();
        if (!$sekolah) {
          return redirect()->back()->withErrors('Simpan data sekolah terlebih dahulu!');
        }
        if (in_array($r->logo->extension(),$ext)) {
          if ($type == 'sekolah') {
            if ($sekolah&&$sekolah->logo&&file_exists(base_path('uploads/'.$sekolah->logo))) {
              unlink(base_path('uploads/'.$sekolah->logo));
            }
            if ($sekolah&&$sekolah->logo&&file_exists(base_path('thumbs/'.$sekolah->logo))) {
              unlink(base_path('thumbs/'.$sekolah->logo));
            }
            $filename = uniqid('logo_').'.'.$r->logo->extension();
            $r->logo->move(base_path('uploads'),$filename);
            $sekolah->logo = $filename;
          }else {
            if ($sekolah&&$sekolah->dept_logo&&file_exists(base_path('uploads/'.$sekolah->dept_logo))) {
              unlink(base_path('uploads/'.$sekolah->dept_logo));
            }
            if ($sekolah&&$sekolah->dept_logo&&file_exists(base_path('thumbs/'.$sekolah->dept_logo))) {
              unlink(base_path('thumbs/'.$sekolah->dept_logo));
            }
            $filename = uniqid('dept_logo_').'.'.$r->logo->extension();
            $r->logo->move(base_path('uploads'),$filename);
            $sekolah->dept_logo = $filename;
          }
          if ($sekolah->save()) {
            return redirect()->back()->with('message', 'Logo berhasil diubah');
          }
        }
      }
      return redirect()->back()->withErrors('File harus berupa gambar jpg, png, atau gif!');
    }

}
