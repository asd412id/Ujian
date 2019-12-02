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
    @php
      $mulai = strtotime($jadwal->mulai_ujian);
      $selesai = strtotime($jadwal->selesai_ujian);
    @endphp
    <div class="container-fluid">
      <div style="position: relative">
        @include('Admin::kop')
      </div>
      <h3 class="text-center" style="padding:0;margin: 0;margin-top: 15px;font-size: 1.5em">NILAI HASIL UJIAN</h3>
      <h3 class="text-center" style="padding:0;margin: 0;margin-bottom: 15px;font-size: 1.5em;text-transform: uppercase">{!! nl2br($jadwal->nama_ujian) !!}</h3>
      <div style="font-size: 1.2em">
        <div class="row">
          <div class="col-sm-6 pull-left" style="max-width: 550px">
            <table class="table table-info">
              <tr>
                <td style="white-space: nowrap">MATA PELAJARAN</td>
                <td align="center" style="width: 15px">:</td>
                <th>{{ $mapel }}</th>
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
          <div class="col-sm-6 pull-right" style="max-width: 350px">
            <table class="table table-info">
              <tr>
                <td>KELAS</td>
                <td align="center">:</td>
                <th>{{ $kelas }}</th>
              </tr>
              <tr>
                <td style="white-space: nowrap">JUMLAH PESERTA</td>
                <td align="center">:</td>
                <th>{{ count(json_decode($jadwal->peserta)).' Orang' }}</th>
              </tr>
              <tr>
                <td>LAMA UJIAN</td>
                <td align="center">:</td>
                <th>{{ $jadwal->lama_ujian.' Menit' }}</th>
              </tr>
              <tr>
                <td>PIN</td>
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
                  $jumlah_soal = $jadwal->jumlah_soal;
                  $plogin = $p->attemptLogin()->where('pin',$jadwal->pin)->first();
                  if ($plogin && $plogin->soal_ujian != '' && !is_null($plogin->soal_ujian)) {
                    $dtes = \App\Models\Tes::where('noujian',$p->noujian)
                    ->where('pin',$jadwal->pin)->whereIn('soal_item',json_decode($plogin->soal_ujian??'[]'))->get();
                    $jumlah_soal = count(json_decode($plogin->soal_ujian??'[]'));
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
                    <td class="text-center">{{ $jumlah_soal }}</td>
                    <td class="text-center">{{ $nbenar }}</td>
                    <td class="text-center">{{ $jumlah_soal-$nbenar }}</td>
                    <td class="text-center">{{ $nilai }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="row" style="margin-bottom: 45px;margin-top: 30px;page-break-inside: avoid !important;">
          <div class="pull-right" style="width: 300px">
            <p>{{ $sekolah->kota.', '.date('d',$mulai).' '.$helper->bulan(date('m',$mulai)).' '.date('Y',$mulai) }}</p>
            <p>Mengetahui</p>
            <p style="margin-bottom: 125px">Kepala {{ $sekolah->nama }}</p>
            <p>[.......................................................]</p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
