<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/fontawesome/css/all.min.css') }}">
    <style media="screen">
      .font-weight-bold{
        font-weight: bold;
      }
      ol li{
        margin-bottom: 10px;
      }
      table.table-info th, table.table-info td{
        border: none !important;
        padding: 3px !important;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <h1 class="text-center">
        Nilai Ujian
      </h1>
      <hr>
      <div class="row">
        <div class="col-sm-6 pull-left">
          <table class="table table-info">
            <tr>
              <td style="width: 150px">NAMA UJIAN</td>
              <td align="center" style="width: 15px">:</td>
              <th>{{ $jadwal->nama_ujian }}</th>
            </tr>
            <tr>
              <td>JENIS SOAL</td>
              <td align="center">:</td>
              <th>{{ $jadwal->jenis_soal?'Pilihan Ganda':'Essay' }}</th>
            </tr>
            <tr>
              <td>JUMLAH SOAL</td>
              <td align="center">:</td>
              <th>{{ $jadwal->jumlah_soal }}</th>
            </tr>
            <tr>
              <td>BOBOT</td>
              <td align="center">:</td>
              <th>{{ $jadwal->bobot }}</th>
            </tr>
          </table>
        </div>
        <div class="col-sm-6 pull-right">
          <table class="table table-info">
            <tr>
              <td>JUMLAH PESERTA</td>
              <td align="center">:</td>
              <th>{{ count(json_decode($jadwal->peserta)).' Orang' }}</th>
            </tr>
            <tr>
              <td>LAMA UJIAN</td>
              <td align="center">:</td>
              <th>{{ $jadwal->lama_ujian.' Menit' }}</th>
            </tr>
            <tr>
              <td style="width: 150px">PIN</td>
              <td align="center" style="width: 15px">:</td>
              <th>{{ $jadwal->pin }}</th>
            </tr>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-bordered">
            <thead>
              <th class="text-center">No.</th>
              <th class="text-center">No. Ujian</th>
              <th class="text-center">Nama Siswa</th>
              <th class="text-center">Kelas</th>
              <th class="text-center">Jumlah Soal</th>
              <th class="text-center">Benar</th>
              <th class="text-center">Salah</th>
              <th class="text-center">Nilai Akhir</th>
            </thead>
            <tbody>
              @php
              @endphp
              @foreach ($peserta as $key => $p)
                @php
                $nilai = 0;
                $nbenar = 0;
                $plogin = $p->attemptLogin()->where('pin',$jadwal->pin)->first();
                if ($plogin) {
                  $dtes = \App\Models\Tes::where('noujian',$p->noujian)
                  ->where('pin',$jadwal->pin)->whereIn('soal_item',json_decode($plogin->soal_ujian))->get();
                  $jumlah_soal = count(json_decode($plogin->soal_ujian));
                  foreach ($dtes as $key1 => $tes) {
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
                }
                @endphp
                <tr>
                  <td class="text-center">{{ ($key+1).'.' }}</td>
                  <td>{{ $p->noujian }}</td>
                  <td>{{ $p->nama }}</td>
                  <td class="text-center">{{ $p->kelas->nama }}</td>
                  <td class="text-center">{{ $jadwal->jumlah_soal }}</td>
                  <td class="text-center">{{ $nbenar }}</td>
                  <td class="text-center">{{ $jadwal->jumlah_soal-$nbenar }}</td>
                  <td class="text-center">{{ $nilai }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row" style="margin-bottom: 45px;margin-top: 30px;page-break-inside: avoid !important;">
        <div class="pull-right" style="width: 300px">
          <p>{{ $sekolah->kota.', '.date('d').' '.$helper->bulan(date('m')).' '.date('Y') }}</p>
          <p>Mengetahui</p>
          <p style="margin-bottom: 125px">Kepala {{ $sekolah->nama }}</p>
          <p>[.......................................................]</p>
        </div>
      </div>
    </div>
  </body>
</html>
