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
      table.table-absen td{
        padding-top: 15px !important;
        padding-bottom: 15px !important;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div style="position: relative">
        @include('Admin::kop')
      </div>
      <h3 class="text-center" style="padding:0;margin: 15px 0;">DAFTAR HADIR PESERTA UJIAN</h3>
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
          <table class="table table-bordered table-absen">
            <thead>
              <th class="text-center">No.</th>
              <th class="text-center">No. Ujian</th>
              <th class="text-center">Nama Siswa</th>
              <th class="text-center">Kelas</th>
              <th class="text-center">Tanda Tangan</th>
            </thead>
            <tbody>
              @php
              @endphp
              @foreach ($peserta as $key => $p)
                <tr>
                  <td class="text-center">{{ ($key+1).'.' }}</td>
                  <td>{{ $p->noujian }}</td>
                  <td>{{ $p->nama }}</td>
                  <td class="text-center">{{ $p->kelas->nama }}</td>
                  <td>
                    <div class="row">
                      <div class="col-xs-7 {{ $key!=0&&($key+1)%2==0?'pull-right':'' }}">
                        {{ $key+1 }}
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row" style="margin-bottom: 45px;margin-top: 30px;page-break-inside: avoid !important;">
        <div class="pull-right" style="width: 300px">
          <p>{{ $sekolah->kota.', '.date('d').' '.$helper->bulan(date('m')).' '.date('Y') }}</p>
          <p style="margin-bottom: 125px">Pengawas Ujian</p>
          <p>[.......................................................]</p>
        </div>
      </div>
    </div>
  </body>
</html>
