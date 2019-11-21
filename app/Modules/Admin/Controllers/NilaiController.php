<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Tes;
use App\Models\JadwalUjian;
use App\Models\Soal;
use App\Models\Siswa;

use GuzzleHttp\Client;
use Spreadsheet;
use Xlsx;
use PDF;

class NilaiController extends Controller
{

    public function index()
    {
      $jadwal = JadwalUjian::with('tes')->paginate(10)->appends(request()->except('page'));
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

    public function download($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();

      $filename = 'Nilai '.$jadwal->getSoal->nama.' '.($jadwal->kelas?$jadwal->kelas->nama.' '.$jadwal->kelas->jurusan:'Semua Kelas').' Sesi '.$jadwal->sesi_ujian.' ('.$jadwal->pin.')'.'.xlsx';

      $spreadsheet = new Spreadsheet();
  		$sheet = $spreadsheet->getActiveSheet();

  		$sheet->setCellValue('A1', 'No');
  		$sheet->setCellValue('B1', 'No. Ujian');
  		$sheet->setCellValue('C1', 'Nama Siswa');
      $sheet->getStyle('A1:C1')->getFont()->setBold(true);
      $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
      $sheet->getStyle('A1:D1')->getAlignment()->setVertical('center');
      $sheet->getStyle('A')->getAlignment()->setHorizontal('center');

      $sheet->mergeCells('A1:A2');
      $sheet->mergeCells('B1:B2');
      $sheet->mergeCells('C1:C2');

      $sheet->getColumnDimension('A')->setWidth(5);
      $sheet->getColumnDimension('B')->setWidth(25);
      $sheet->getColumnDimension('C')->setWidth(30);

      $pg = range('A','Z');
      $cols = $this->createColumnsArray('ZZ');
      $cols = array_slice($cols,3,count($cols));
      $soal = $jadwal->getSoal->item()->where('jenis_soal','P')->get();

      if (!count($soal)) {
        return $this->downloadEssay($uuid);
      }
      $jsoal = $soal[0]->jenis_soal;

      $suuid = [];

      $sheet->setCellValue('D1', 'No. Soal');
      $sheet->getStyle('D1')->getFont()->setBold(true);

      foreach ($soal as $key => $s) {
        array_push($suuid,$s->uuid);
        if ($jsoal=='P'&&array_key_exists($s->benar,$pg)) {
          $pgb = !is_null($s->benar)||$s->benar!='null'||$s->benar!=''?'('.$pg[$s->benar].')':'';
          $sheet->setCellValue($cols[$key].'2', ($key+1).$pgb);
        }else {
          $sheet->setCellValue($cols[$key].'2', ($key+1));
        }

        $sheet->getStyle($cols[$key].'2')->getFont()->setBold(true);
        $sheet->getStyle($cols[$key].'2')->getAlignment()->setHorizontal('center');
        if ($jsoal=='P') {
          $sheet->getColumnDimension($cols[$key])->setwidth(7);
        }else {
          $sheet->getColumnDimension($cols[$key])->setwidth(30);
        }
      }

      $sheet->mergeCells('D1:'.$cols[$key].'1');

      $key++;
      $sheet->setCellValue($cols[$key].'1', 'Jumlah Soal');
      $sheet->getStyle($cols[$key].'1')->getFont()->setBold(true);
      $sheet->getStyle($cols[$key].'1')->getAlignment()->setHorizontal('center');
      $sheet->getStyle($cols[$key].'1')->getAlignment()->setVertical('center');
      $sheet->getColumnDimension($cols[$key])->setAutoSize(true);
      $sheet->mergeCells($cols[$key].'1:'.$cols[$key].'2');

      if ($jsoal=='P') {
        $key++;
        $sheet->setCellValue($cols[$key].'1', 'Benar');
        $sheet->getStyle($cols[$key].'1')->getFont()->setBold(true);
        $sheet->getStyle($cols[$key].'1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle($cols[$key].'1')->getAlignment()->setVertical('center');
        $sheet->getColumnDimension($cols[$key])->setAutoSize(true);
        $sheet->mergeCells($cols[$key].'1:'.$cols[$key].'2');

        $key++;
        $sheet->setCellValue($cols[$key].'1', 'Salah');
        $sheet->getStyle($cols[$key].'1')->getFont()->setBold(true);
        $sheet->getStyle($cols[$key].'1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle($cols[$key].'1')->getAlignment()->setVertical('center');
        $sheet->getColumnDimension($cols[$key])->setAutoSize(true);
        $sheet->mergeCells($cols[$key].'1:'.$cols[$key].'2');
      }

      $key++;
      $sheet->setCellValue($cols[$key].'1', 'Nilai Akhir');
      $sheet->getStyle($cols[$key].'1')->getFont()->setBold(true);
      $sheet->getStyle($cols[$key].'1')->getAlignment()->setHorizontal('center');
      $sheet->getStyle($cols[$key].'1')->getAlignment()->setVertical('center');
      $sheet->getColumnDimension($cols[$key])->setAutoSize(true);
      $sheet->mergeCells($cols[$key].'1:'.$cols[$key].'2');

      $inc = [];
      $peserta = $jadwal->kelas?$jadwal->kelas->siswa()->orderBy('id','asc')->get():Siswa::orderBy('id','asc')->get();
      $i=2;
      foreach ($peserta as $key => $v) {
        if (!in_array($v->noujian,$inc)) {
          array_push($inc,$v->noujian);
          $sheet->setCellValue('A'.($i+1), $i-1);
          $sheet->setCellValue('B'.($i+1), $v->noujian);
          $sheet->setCellValue('C'.($i+1), $v->nama);
          $benar = 0;
          foreach ($soal as $key1 => $s) {
            $jawaban = @Tes::where('noujian',$v->noujian)->where('soal_item',$s->uuid)->where('pin',$jadwal->pin)->first()->jawaban;

            $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setHorizontal('center');

            $color = array_key_exists($jawaban,$pg)?'00ff0c0c':'';
            if (array_key_exists($jawaban,$pg)) {
              // if (is_null($benar)) {
              //   $benar = 0;
              // }
              if ((string) $jawaban == (string) $s->benar) {
                $benar++;
                $color = '00008000';
              }
            }

            $sheet->getStyle($cols[$key1].($i+1))->getFont()->setBold(true);
            $sheet->getStyle($cols[$key1].($i+1))->getFont()->getColor()->setARGB($color);

            $jawab = '';

            if (@Tes::where('noujian',$v->noujian)->where('soal_item',$s->uuid)->where('pin',$jadwal->pin)->first()->jawaban!=null||@Tes::where('noujian',$v->noujian)->where('soal_item',$s->uuid)->where('pin',$jadwal->pin)->first()->jawaban!='') {
              $jwb = Tes::where('noujian',$v->noujian)->where('soal_item',$s->uuid)->where('pin',$jadwal->pin)->first()->jawaban;
              if (array_key_exists($jwb,$pg)) {
                $jawab = $pg[Tes::where('noujian',$v->noujian)->where('soal_item',$s->uuid)->where('pin',$jadwal->pin)->first()->jawaban];
              }else {
                $jawab = $jwb;
              }
              $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setVertical('center');
            }else {
              $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setHorizontal('left');
              $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setWrapText(true);
              $sheet->getRowDimension($cols[$key1].($i+1))->setRowHeight(15);
            }

            $sheet->setCellValue($cols[$key1].($i+1), $jawab);
          }

          $nilai = 0;
          if (!is_null($benar)) {
            $nilai = round($benar/count($soal)*$jadwal->getSoal->bobot,2);
          }

          $key1++;
          $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setHorizontal('center');
          $sheet->setCellValue($cols[$key1].($i+1),count($soal));

          if ($jsoal=='P') {
            $key1++;
            $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setHorizontal('center');
            $sheet->setCellValue($cols[$key1].($i+1),$benar);

            $key1++;
            $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setHorizontal('center');
            $sheet->setCellValue($cols[$key1].($i+1),count($soal)-$benar);
          }

          $key1++;
          $sheet->getStyle($cols[$key1].($i+1))->getAlignment()->setHorizontal('center');
          $sheet->getStyle($cols[$key1].($i+1))->getFont()->setBold(true);
          $sheet->setCellValue($cols[$key1].($i+1),$nilai);

          $i++;
        }
      }

      $writer = new Xlsx($spreadsheet);
  		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="'.$filename.'"');
  		$writer->save("php://output");
    }

    public function downloadEssay($uuid)
    {
      $jadwal = JadwalUjian::where('uuid',$uuid)->first();

      $filename = 'Nilai '.$jadwal->getSoal->nama.' '.($jadwal->kelas?$jadwal->kelas->nama.' '.$jadwal->kelas->jurusan:'Semua Kelas').' Sesi '.$jadwal->sesi_ujian.' ('.$jadwal->pin.')'.'.pdf';

      $peserta = $jadwal->kelas?$jadwal->kelas->siswa:Siswa::all();

      $view = view('Admin::nilai.setnilai',[
        'jadwal'=>$jadwal,
        'peserta'=>$peserta,
        'title'=>$filename
      ])->render();

      $client = new Client;
      $res = $client->request('POST','http://pdf/pdf',[
        'form_params'=>[
          'html'=>str_replace(url('/'),'http://nginx_ujian/',$view),
          'options[page-width]'=>'21.5cm',
          'options[page-height]'=>'33cm',
          'options[margin-top]'=>'0.5cm',
          'options[margin-bottom]'=>'0',
          'options[margin-left]'=>'0',
          'options[margin-right]'=>'0',
        ]
      ]);

      if ($res->getStatusCode() == 200) {
        return response()->attachment($res->getBody()->getContents(),$filename,'application/pdf');
      }

      return redirect()->back()->withErrors(['Tidak dapat mendownload file! Silahkan hubungi operator']);

      // $pdf = PDF::loadView('Admin::nilai.setnilai',[
      //   'jadwal'=>$jadwal,
      //   'peserta'=>$peserta,
      //   'title'=>$filename
      // ]);

      // return $pdf->setPaper('a4')
      // ->setOption('margin-top',3)
      // ->setOption('margin-bottom',0)
      // ->setOption('margin-left',0)
      // ->setOption('margin-right',0)
      // ->stream($filename);

    }

}
