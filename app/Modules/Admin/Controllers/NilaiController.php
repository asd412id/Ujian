<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Tes;
use App\Models\JadwalUjian;
use App\Models\Soal;
use App\Models\ItemSoal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Modules\Admin\Helper;

use GuzzleHttp\Client;
use Spreadsheet;
use Xlsx;
use PDF;

class NilaiController extends Controller
{

    public function index()
    {
      $cari = request()->cari;
      $jadwal = JadwalUjian::with('tes')
      ->when($cari,function($jadwal,$role){
        $r = '%'.$role.'%';
        $jadwal->where('nama_ujian','like',$r)
        ->orWhere('pin','like',$r)
        ->orWhereHas('login.tes.soalItem.getSoal.mapel',function($soal) use($jadwal,$r){
          $soal->where('nama','like',$r);
        })
        ->orWhereHas('login.siswa.kelas',function($kelas) use($jadwal,$r){
          $kelas->where('nama','like',$r);
        });
      })
      ->paginate(30)->appends(request()->except('page'));
      return view("Admin::nilai.index",[
        'title'=>'Nilai Ujian - Administrator',
        'breadcrumb'=>'Nilai Ujian',
        'jadwal'=>$jadwal,
        'tes'=>new Tes
      ]);
    }

    public function createColumnsArray($end_column, $first_letters = '')
    {
      $columns = array();
      $length = strlen($end_column);
      $letters = range('A', 'Z');

      // Iterate over 26 letters.
      foreach ($letters as $letter) {
          // Paste the $first_letters before the next.
          $column = $first_letters . $letter;

          // Add the column to the final array.
          $columns[] = $column;

          // If it was the end column that was added, return the columns.
          if ($column == $end_column)
              return $columns;
      }

      // Add the column children.
      foreach ($columns as $column) {
          // Don't itterate if the $end_column was already set in a previous itteration.
          // Stop iterating if you've reached the maximum character length.
          if (!in_array($end_column, $columns) && strlen($column) < $length) {
              $new_columns = $this->createColumnsArray($end_column, $column);
              // Merge the new columns which were created with the final columns array.
              $columns = array_merge($columns, $new_columns);
          }
      }

      return $columns;
    }

    public function downloadExcel($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();

      $filename = 'Nilai '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).'.xlsx';

      $spreadsheet = new Spreadsheet();
  		$sheet = $spreadsheet->getActiveSheet();

  		$sheet->setCellValue('A1', 'No');
  		$sheet->setCellValue('B1', 'No. Ujian');
  		$sheet->setCellValue('C1', 'Nama Siswa');
  		$sheet->setCellValue('D1', 'Kelas');
      $sheet->getStyle('A1:D1')->getFont()->setBold(true);
      $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
      $sheet->getStyle('A1:D1')->getAlignment()->setVertical('center');
      $sheet->getStyle('A')->getAlignment()->setHorizontal('center');
      $sheet->getStyle('D')->getAlignment()->setHorizontal('center');

      $sheet->getColumnDimension('A')->setWidth(5);
      $sheet->getColumnDimension('B')->setWidth(20);
      $sheet->getColumnDimension('C')->setWidth(30);
      $sheet->getColumnDimension('D')->setAutoSize(true);

      $cols = $this->createColumnsArray('ZZ');
      $cols = array_slice($cols,3,count($cols));

      // if ($jadwal->jenis_soal == 'E') {
      //   return $this->downloadEssay($uuid);
      // }

      $sheet->setCellValue('E1', 'Jumlah Soal');
      $sheet->getStyle('E1')->getFont()->setBold(true);
      $sheet->getStyle('E1')->getAlignment()->setHorizontal('center');
      $sheet->getStyle('E1')->getAlignment()->setVertical('center');
      $sheet->getColumnDimension('E')->setAutoSize(true);

      if ($jadwal->jenis_soal=='P') {
        $sheet->setCellValue('F1', 'Benar');
        $sheet->getStyle('F1')->getFont()->setBold(true);
        $sheet->getStyle('F1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F1')->getAlignment()->setVertical('center');
        $sheet->getColumnDimension('F')->setAutoSize(true);

        $sheet->setCellValue('G1', 'Salah');
        $sheet->getStyle('G1')->getFont()->setBold(true);
        $sheet->getStyle('G1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G1')->getAlignment()->setVertical('center');
        $sheet->getColumnDimension('G')->setAutoSize(true);
      }

      $sheet->setCellValue('H1', 'Nilai Akhir');
      $sheet->getStyle('H1')->getFont()->setBold(true);
      $sheet->getStyle('H1')->getAlignment()->setHorizontal('center');
      $sheet->getStyle('H1')->getAlignment()->setVertical('center');
      $sheet->getColumnDimension('H')->setAutoSize(true);

      $peserta = Siswa::whereIn('uuid',json_decode($jadwal->peserta))
      ->orderBy('id','asc')
      ->get();
      $i=1;
      foreach ($peserta as $key => $v) {
        $sheet->setCellValue('A'.($i+1), $i);
        $sheet->setCellValue('B'.($i+1), $v->noujian??'-');
        $sheet->setCellValue('C'.($i+1), $v->nama??'-');
        $sheet->setCellValue('D'.($i+1), $v->kelas?$v->kelas->nama:'-');
        $benar = 0;
        $nilai = 0;

        $login = $v->attemptLogin()->where('pin',$jadwal->pin)->first();
        if ($login && $login->soal_ujian != '' && !is_null($login->soal_ujian)) {
          $soal = ItemSoal::whereIn('uuid',json_decode($login->soal_ujian))->get();
          foreach ($soal as $key1 => $s) {
            $tes = Tes::where('noujian',$v->noujian)->where('soal_item',$s->uuid)->where('pin',$jadwal->pin)->first();

            if ($tes && array_key_exists($tes->jawaban,json_decode($s->opsi))) {
              if ((string) $tes->jawaban == (string) $s->benar) {
                $benar++;
              }
            }

          }

          if ($soal->count()) {
            $nilai = 0;
          }

          if ($benar) {
            $nilai += round($benar/count(json_decode($login->soal_ujian))*$jadwal->bobot,2);
          }

        }

        $sheet->getStyle('E'.($i+1))->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('E'.($i+1),count(json_decode($login->soal_ujian??'[]')));

        if ($jadwal->jenis_soal=='P') {
          $sheet->getStyle('F'.($i+1))->getAlignment()->setHorizontal('center');
          $sheet->setCellValue('F'.($i+1),$benar);

          $sheet->getStyle('G'.($i+1))->getAlignment()->setHorizontal('center');
          $sheet->setCellValue('G'.($i+1),count(json_decode($login->soal_ujian??'[]'))-$benar);
        }

        $sheet->getStyle('H'.($i+1))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H'.($i+1))->getFont()->setBold(true);
        $sheet->setCellValue('H'.($i+1),$nilai);

        $i++;
      }

      $writer = new Xlsx($spreadsheet);
  		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="'.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).'.xlsx"');
  		$writer->save("php://output");
    }

    public function downloadPDF($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();

      if (!$jadwal) {
        return redirect()->route('nilai.index')->withErrors('Jadwal ujian tidak ditemukan');
      }

      $filename = 'Nilai '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).'.pdf';

      $peserta = Siswa::whereIn('uuid',json_decode($jadwal->peserta))
      ->orderBy('id','asc')
      ->get();

      if (!$peserta) {
        return redirect()->route('nilai.index')->withErrors('Peserta ujian tidak ditemukan');
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

      $pdf = PDF::loadView('Admin::nilai.nilai-pdf',[
        'jadwal'=>$jadwal,
        'peserta'=>$peserta,
        'kelas'=>$kelas,
        'mapel'=>$mapel,
        'title'=>'Nilai '.$jadwal->nama_ujian,
        'sekolah'=>Sekolah::first(),
        'helper'=>new Helper
      ]);

      return $pdf->stream($filename);

    }

    public function detail($uuid)
    {
      $jadwal = JadwalUjian::with('tes')->where('uuid',$uuid)->first();

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

      return view("Admin::nilai.detail",[
        'title'=>'Nilai '.str_replace(["\r\n","\r","\n"]," ",$jadwal->nama_ujian).' - Administrator',
        'breadcrumb'=>'Nilai Ujian',
        'jadwal'=>$jadwal,
        'kelas'=>$kelas,
        'mapel'=>$mapel,
        'peserta'=>Siswa::whereIn('uuid',json_decode($jadwal->peserta))->orderBy('id','asc')->get(),
      ]);
    }

    public function detailDownload($ujian,$siswa)
    {
      $nilai = 0;
      $nbenar = 0;
      $mapel = '';
      $jadwal = JadwalUjian::with('tes')->where('uuid',$ujian)->first();
      $siswa = Siswa::where('uuid',$siswa)->first();
      $plogin = $siswa->attemptLogin()->where('pin',$jadwal->pin)->first();
      $jumlah_soal = @count(json_decode($plogin->soal_ujian));
      $dtes = Tes::where('noujian',$siswa->noujian)
      ->where('pin',$jadwal->pin)->whereIn('soal_item',json_decode($plogin->soal_ujian??'[]'))->get();

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

      $soal = [];
      $siswaSoal = json_decode($plogin->soal_ujian??'[]');
      if (count($siswaSoal)) {
        foreach ($siswaSoal as $key => $s) {
          $gs = ItemSoal::where('uuid',$s)->first();
          if ($gs) {
            array_push($soal,$gs);
          }
        }
      }
      foreach ($dtes as $key => $tes) {
        $benar = $tes->soalItem->benar;
        if (!is_null($benar) && (string) $tes->jawaban == (string) $benar && $tes->soalItem->jenis_soal=='P') {
          $nbenar++;
        }
      }
      if ($jumlah_soal) {
        $nilai = 0;
      }
      if ($nbenar) {
        $nilai += round($nbenar/$jumlah_soal*$jadwal->bobot,2);
      }

      $filename = '('.$siswa->noujian.') '.$siswa->nama.'.pdf';

      $pdf = PDF::loadView("Admin::nilai.detail-download",[
        'title'=>'('.$siswa->noujian.') '.$siswa->nama,
        'jadwal'=>$jadwal,
        'siswa'=>$siswa,
        'soal'=>$soal,
        'mapel'=>$mapel,
        'nilai'=>$nilai,
        'benar'=>$nbenar,
        'sekolah'=>Sekolah::first(),
        'helper'=>new Helper
      ]);

      return $pdf->stream($filename);

    }

    public function downloadSoal($uuid)
    {
      $mapel = '';
      $kelas = '';
      $soal = [];
      $jadwal = JadwalUjian::with('tes')->where('uuid',$uuid)->first();

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

      $getSoal = Soal::whereIn('uuid',json_decode($jadwal->soal))->with('item')->get();

      foreach ($getSoal as $key => $gs) {
        foreach ($gs->item as $key => $si) {
          array_push($soal,$si);
        }
      }

      $filename = strip_tags(str_replace(["\r\n","\n","\r"],'',$jadwal->nama_ujian)).' ('.$mapel.') ('.$jadwal->pin.')';

      $pdf = PDF::loadView("Admin::nilai.download-soal",[
        'title'=>$filename,
        'jadwal'=>$jadwal,
        'kelas'=>$kelas,
        'soal'=>$soal,
        'mapel'=>$mapel,
        'sekolah'=>Sekolah::first(),
        'helper'=>new Helper
      ]);

      return $pdf->stream($filename.'.pdf');

    }

}
