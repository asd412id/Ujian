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
      table.table-info tr th{
        white-space: normal;
      }
      img{
        max-width: 100% !important;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div style="position: relative">
        @include('Admin::kop')
      </div>
      <h3 class="text-center" style="padding:0;margin: 0;margin-top: 15px;font-size: 1.5em">NILAI HASIL UJIAN</h3>
      <h3 class="text-center" style="padding:0;margin: 0;margin-bottom: 15px;font-size: 1.5em;text-transform: uppercase">{!! nl2br($jadwal->nama_ujian) !!}</h3>
      <div style="font-size: 1.2em">
        <div class="row">
          <div class="col-sm-6" style="float: left !important;max-width: 45% !important;white-space: nowrap">
            <table class="table table-info">
              <tr>
                <td>NOMOR UJIAN</td>
                <td align="center" style="width: 15px">:</td>
                <th>{{ $siswa->noujian }}</th>
              </tr>
              <tr>
              <tr>
                <td>NAMA</td>
                <td align="center">:</td>
                <th>{{ $siswa->nama }}</th>
              </tr>
              <tr>
                <td>KELAS</td>
                <td align="center">:</td>
                <th>{{ $siswa->kelas->nama }}</th>
              </tr>
            </table>
          </div>
          <div class="col-sm-6" style="float: right !important;max-width: 45% !important;white-space: nowrap">
            <table class="table table-info">
              <tr>
                <td>MATA PELAJARAN</td>
                <td align="center" style="width: 15px">:</td>
                <th>{{ $mapel }}</th>
              </tr>
              <tr>
                <td>JUMLAH SOAL</td>
                <td align="center">:</td>
                <th>{{ count($soal) }}</th>
              </tr>
              <tr>
                <td>BOBOT</td>
                <td align="center">:</td>
                <th>{{ $jadwal->bobot }}</th>
              </tr>
              @if ($jadwal->jenis_soal=='P')
                <tr>
                  <th colspan="3" style="vertical-align: top !important;border: none"><h1 style="position: relative;font-size: 4em"><span style="font-size: 19px; position: relative;top: -35px;">NILAI AKHIR: </span>{{ $nilai }}</h1></th>
                </tr>
              @endif
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            @if ($jadwal->jenis_soal=='P')
              @include('Admin::nilai.detail.p')
            @else
              @include('Admin::nilai.detail.e')
            @endif
          </div>
        </div>
        <div class="row" style="margin-bottom: 45px;page-break-inside: avoid !important;">
          <div class="col-sm-12">
            <div class="pull-left" style="white-space: nowrap">
              <table class="table table-bordered">
                <tr>
                  <th colspan="3">Keterangan</th>
                </tr>
                @php
                  $terjawab = App\Models\Tes::where('noujian',$siswa->noujian)->where('pin',$siswa->attemptLogin()->where('pin',$jadwal->pin)->first()->pin)->whereIn('soal_item',json_decode($siswa->attemptLogin()->where('pin',$jadwal->pin)->first()->soal_ujian??'[]'))->whereNotNull('jawaban')->count();
                @endphp
                <tr>
                  <td>Terjawab</td>
                  <td>:</td>
                  <td>{{ $terjawab }}</td>
                </tr>
                <tr>
                  <td>Tidak Dijawab</td>
                  <td>:</td>
                  <td>{{ (count(json_decode($siswa->attemptLogin()->where('pin',$jadwal->pin)->first()->soal_ujian??'[]')))-$terjawab }}</td>
                </tr>
                @if ($jadwal->jenis_soal == 'P')
                  <tr>
                    <td>Jawaban Benar</td>
                    <td>:</td>
                    <td>{{ $benar }}</td>
                  </tr>
                  <tr>
                    <td>Jawaban Salah</td>
                    <td>:</td>
                    <td>{{ $jadwal->jumlah_soal-$benar }}</td>
                  </tr>
                @endif
              </table>
            </div>
            <div class="pull-right" style="white-space: nowrap">
              <p>{{ $sekolah->kota.', '.date('d').' '.$helper->bulan(date('m')).' '.date('Y') }}</p>
              <p style="margin-bottom: 125px">Guru Mata Pelajaran</p>
              <p>[.........................................................]</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
