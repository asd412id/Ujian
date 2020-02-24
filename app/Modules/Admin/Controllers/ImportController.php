<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\JadwalUjian;
use App\Models\Soal;
use App\Models\ItemSoal;

use Illuminate\Support\Str;
use Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ImportController extends Controller
{

    public function import(Request $r)
    {

      if ($r->file('excel')->isValid()) {

        $ext = ['xlsx','xls','bin','ods'];
        if (in_array($r->excel->extension(),$ext)) {
          if ($r->excel->extension()=='ods') {
            $reader = new Ods;
          }else {
            $reader = new Xlsx;
          }
          $spreadsheet = $reader->load($r->excel->path());

          $sheets = $spreadsheet->getSheetNames();

          $errors = [];

          foreach ($sheets as $key => $s) {
            if (method_exists($this,trim(strtolower($s)))) {
              $arr = $spreadsheet->getSheet($key)->toArray();
              $data = call_user_func_array([$this,trim(strtolower($s))],[$arr]);
              if ($data!==true) {
                array_push($errors,$data);
              }
            }
          }

          if (!count($errors)) {
            return redirect()->back()->with('message', 'Data berhasil diimpor.');
          }else {
            return redirect()->back()->withErrors($errors);
          }

        }

      }
      return redirect()->back()->withErrors('File bukan file Excel/Spreadsheet!');

    }

    public function sekolah($data)
    {
      if (is_array($data)) {
        $insert = [];
        foreach ($data as $key => $row) {
          if (trim(strtolower($row[0]))=='kode') {
            $insert['kode'] = trim($row[2]);
          }elseif (trim(strtolower($row[0]))=='nama') {
            $insert['nama'] = trim($row[2]);
          }elseif (trim(strtolower($row[0]))=='alamat') {
            $insert['alamat'] = trim($row[2]);
          }elseif (trim(strtolower($row[0]))=='kota') {
            $insert['kota'] = trim($row[2]);
          }elseif (trim(strtolower($row[0]))=='propinsi') {
            $insert['propinsi'] = trim($row[2]);
          }elseif (trim(strtolower($row[0]))=='kode pos') {
            $insert['kodepos'] = trim($row[2]);
          }elseif (trim(strtolower($row[0]))=='no. telepon') {
            $insert['telp'] = trim($row[2]);
          }elseif (trim(strtolower($row[0]))=='fax') {
            $insert['fax'] = trim($row[2]);
          }
        }

        if ($insert['kode']=='') {
          return $err = 'Kode Sekolah harus diisi!';
        }elseif ($insert['nama']=='') {
          return $err = 'Nama Sekolah harus diisi!';
        }

        $import = Sekolah::where('kode',$insert['kode'])->first();
        if (!$import) {
          $import = new Sekolah;
          $import->truncate();
          $import->uuid = (string)Str::uuid();
        }
        $import->kode = $insert['kode'];
        $import->nama = $insert['nama'];
        $import->alamat = $insert['alamat'];
        $import->kota = $insert['kota'];
        $import->propinsi = $insert['propinsi'];
        $import->kodepos = $insert['kodepos'];
        $import->telp = $insert['telp'];
        $import->fax = $insert['fax'];

        return $import->save();
      }

      return false;

    }

    public function kelas($data)
    {
      $status = true;
      if (is_array($data)) {
        $insert = [];
        foreach ($data as $key => $row) {
          if ($key>0) {
            $insert['kode'] = $row[0];
            $insert['nama'] = $row[1];
            $insert['jurusan'] = $row[2];
            $insert['tingkat'] = $row[3];

            if ($insert['kode']=='') {
              return $err = 'Kode Kelas harus diisi!';
            }elseif ($insert['nama']=='') {
              return $err = 'Nama Kelas harus diisi!';
            }

            $import = Kelas::where('kode',$insert['kode'])->first();
            if (!$import) {
              $import = new Kelas;
              $import->uuid = (string)Str::uuid();
            }
            $import->kode = $insert['kode'];
            $import->nama = $insert['nama'];
            $import->jurusan = $insert['jurusan'];
            $import->tingkat = $insert['tingkat'];

            $status = $import->save();
          }
        }

      }

      return $status;
    }

    public function mapel($data)
    {
      $status = true;
      if (is_array($data)) {
        $insert = [];
        foreach ($data as $key => $row) {
          if ($key>0) {
            $insert['kode'] = $row[0];
            $insert['nama'] = $row[1];

            if ($insert['kode']=='') {
              return $err = 'Kode Mata Pelajaran harus diisi!';
            }elseif ($insert['nama']=='') {
              return $err = 'Nama Mata Pelajaran harus diisi!';
            }

            $import = Mapel::where('kode',$insert['kode'])->first();
            if (!$import) {
              $import = new Mapel;
              $import->uuid = (string)Str::uuid();
            }
            $import->kode = $insert['kode'];
            $import->nama = $insert['nama'];

            $status = $import->save();
          }
        }

      }

      return $status;
    }

    public function siswa($data)
    {
      $status = true;
      if (is_array($data)) {
        $insert = [];
        foreach ($data as $key => $row) {
          if ($key>0) {
            $insert['noujian'] = $row[0];
            $insert['nik'] = $row[1];
            $insert['nama'] = $row[2];
            $insert['kode_kelas'] = $row[3];
            $insert['password'] = $row[4]!=''?bcrypt($row[4]):bcrypt($row[0]);
            $insert['photo'] = $row[5];

            if ($insert['noujian']=='') {
              return $err = 'Nomor ujian tidak boleh kosong!';
            }elseif ($insert['nama']=='') {
              return $err = 'Nama siswa harus diisi!';
            }

            $import = Siswa::where('noujian',$insert['noujian'])->first();
            if (!$import) {
              $import = new Siswa;
              $import->uuid = (string)Str::uuid();
            }
            $import->noujian = $insert['noujian'];
            $import->nik = $insert['nik'];
            $import->nama = $insert['nama'];
            $import->kode_kelas = $insert['kode_kelas'];
            $import->password = $insert['password'];
            $import->real_password = $row[4]!=''?$row[4]:$row[0];
            $import->photo = $insert['photo'];

            $status = $import->save();
          }
        }

      }

      return $status;
    }

    public function importSoal(Request $r)
    {
      $ext = ['xlsx','xls','bin','ods'];
      if (in_array($r->excel->extension(),$ext)) {
        if ($r->excel->extension()=='ods') {
          $reader = new Ods;
        }else {
          $reader = new Xlsx;
        }
        $spreadsheet = $reader->load($r->excel->path());

        $sheet = $spreadsheet->getSheet(0);

        if (trim($sheet->getCell('C1')->getValue())=='') {
          return redirect()->back()->withErrors('Kode Bank Soal tidak boleh kosong!');
        }elseif (trim($sheet->getCell('C2')->getValue())=='') {
          return redirect()->back()->withErrors('Kode Kelas tidak boleh kosong!');
        }elseif (trim($sheet->getCell('C3')->getValue())=='') {
          return redirect()->back()->withErrors('Kode Mata Pelajaran tidak boleh kosong!');
        }elseif (trim($sheet->getCell('C4')->getValue())=='') {
          return redirect()->back()->withErrors('Jenis soal harus dipilih!');
        }

        $kode = trim($sheet->getCell('C1')->getValue());
        $nama = trim($sheet->getCell('C2')->getValue());
        $mapel = Mapel::where('kode',trim($sheet->getCell('C3')->getValue()))->first();
        $jenis_soal = trim($sheet->getCell('C4')->getValue())=='Pilihan Ganda'?'P':'E';
        $acak_opsi = trim($sheet->getCell('C5')->getValue())=='Ya'?'Y':'N';

		if (!$mapel) {
          return redirect()->back()->withErrors('Kode mata pelajaran tidak tersedia!');
        }elseif (!$jenis_soal) {
          return redirect()->back()->withErrors('Jenis soal tidak boleh kosong!');
        }elseif ($jenis_soal != 'P' && $jenis_soal != 'E') {
          return redirect()->back()->withErrors('Jenis soal tidak benar! Pastikan anda menggunakan template soal untuk mengimport!');
        }

        $bank = Soal::where('kode',$kode)->first();
        if (!$bank) {
          $bank = new Soal;
          $bank->uuid = (string)Str::uuid();
          $bank->kode = $kode;
        }
        if ($bank->tes) {
          $bank->tes()->forceDelete();
        }
        if ($bank->item) {
          $bank->item()->forceDelete();
        }
        $bank->nama = $nama;
        $bank->kode_mapel = $mapel->kode;

        $status = false;

        if ($bank->save()) {
          $sheet = $spreadsheet->getSheet(1)->toArray();

          foreach ($sheet as $key => $row) {
            if ($key > 0) {
              $cols = range('A','Z');

              if (!is_numeric($row[0])) {
                continue;
              }

              $soal = $spreadsheet->getSheet(1)->getCell($cols[1].($key+1))->getValue();

              if (!$soal||$soal=='') {
                return redirect()->back()->withErrors('Soal tidak boleh kosong!');
              }

              if ($soal instanceof RichText) {
                $newsoal = '';
                foreach ($soal->getRichTextElements() as $richTextElement) {
                  if ($richTextElement->getFont()) {
                    $st = 0;
                    $styles = '';
                    if ($richTextElement->getFont()->getBold() === true) {
                      $st = 1;
                      $styles = $styles == '' ? $richTextElement->getText() : $styles;
                      $styles = sprintf('<strong>%s</strong>',$styles);
                    }
                    if ($richTextElement->getFont()->getItalic() === true) {
                      $st = 1;
                      $styles = $styles == '' ? $richTextElement->getText() : $styles;
                      $styles = sprintf('<em>%s</em>',$styles);
                    }
                    if ($richTextElement->getFont()->getUnderline() !== 'none') {
                      $st = 1;
                      $styles = $styles == '' ? $richTextElement->getText() : $styles;
                      $styles = sprintf('<u>%s</u>',$styles);
                    }
                    if ($richTextElement->getFont()->getStrikethrough() === true) {
                      $st = 1;
                      $styles = $styles == '' ? $richTextElement->getText() : $styles;
                      $styles = sprintf('<strike>%s</strike>',$styles);
                    }
                    if ($richTextElement->getFont()->getColor()->getRGB() != '000000') {
                      $st = 1;
                      $styles = $styles == '' ? $richTextElement->getText() : $styles;
                      $styles = sprintf('<span style="color: #'.$richTextElement->getFont()->getColor()->getRGB().'">%s</span>', $styles);
                    }
                    if ($richTextElement->getFont()->getSuperscript() === true) {
                      $st = 1;
                      $styles = $styles == '' ? $richTextElement->getText() : $styles;
                      $styles = sprintf('<sup>%s</sup>',$styles);
                    }
                    if ($richTextElement->getFont()->getSubscript() === true) {
                      $st = 1;
                      $styles = $styles == '' ? $richTextElement->getText() : $styles;
                      $styles = sprintf('<sub>%s</sub>',$styles);
                    }

                    if (!$st) {
                      $newsoal .= $richTextElement->getText();
                    }
                    $newsoal .= $styles;
                  }else {
                    $newsoal .= $richTextElement->getText();
                  }
                }
                $soal = $newsoal;
              }

              $opsi_count = $row[2];

              $opsi = null;
              $benar = null;

              $opsi = [];
              if ($opsi_count) {
                for ($i=0; $i < $opsi_count; $i++) {
                  // array_push($opsi,$row[$i+3]);

                  $getopsi = $spreadsheet->getSheet(1)->getCell($cols[$i+3].($key+1))->getValue();

                  if ($getopsi instanceof RichText) {
                    $newopsi = '';
                    foreach ($getopsi->getRichTextElements() as $richTextElement) {
                      if ($richTextElement->getFont()) {
                        $st = 0;
                        $styles = '';
                        if ($richTextElement->getFont()->getSuperscript() === true) {
                          $st = 1;
                          $styles = $styles == '' ? $richTextElement->getText() : $styles;
                          $styles = sprintf('<sup>%s</sup>',$styles);
                        }
                        if ($richTextElement->getFont()->getSubscript() === true) {
                          $st = 1;
                          $styles = $styles == '' ? $richTextElement->getText() : $styles;
                          $styles = sprintf('<sub>%s</sub>',$styles);
                        }

                        if (!$st) {
                          $newopsi .= $richTextElement->getText();
                        }
                        $newopsi .= $styles;
                      }else {
                        $newopsi .= $richTextElement->getText();
                      }
                    }
                    $getopsi = $newopsi;
                  }

                  array_push($opsi,$getopsi);

                  if ($spreadsheet->getSheet(1)->getStyle($cols[$i+3].($key+1))->getFont()->getBold()) {
                    $benar = $i;
                  }

                }
              }

              $item = new ItemSoal;
              $item->uuid = (string) Str::uuid();
              $item->kode_soal = $kode;
              $item->jenis_soal = $jenis_soal;
              $item->soal = $soal;
              $item->acak_opsi = $acak_opsi;
              $item->opsi = json_encode($opsi);

              $item->benar = $benar;
              $status = $item->save();

            }
          }
          if ($status) {
            return redirect()->back()->with('message','Soal berhasil diimpor!');
          }
        }

      }
    }
}
