<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    @include('print-style')
    <style>
      .font-weight-bold{
        font-weight: bold;
      }
      ol li{
        margin-bottom: 10px;
      }
      table.table-info th, table.table-info td{
        border: none !important;
        padding: 3px !important;
        vertical-align: top;
      }
      table.table-info tr th{
        white-space: normal;
      }
      table.table-nilai th,table.table-nilai td{
        padding: 3px 7px !important;
        border-color: #000;
      }
      table.table-nilai th{
        white-space: nowrap;
      }
      @page{
        margin: 20px 50px;
      }
    </style>
  </head>
  <body>
    @php
      $mulai = strtotime($jadwal->mulai_ujian);
      $selesai = strtotime($jadwal->selesai_ujian);
    @endphp
    @include('Admin::kop')
    <div class="container-fluid">
      <h4 class="text-center" style="padding:0;margin: 0">NILAI HASIL UJIAN</h4>
      <h4 class="text-center" style="padding:0;margin: 0;text-transform: uppercase;margin-bottom: 15px">{!! nl2br($jadwal->nama_ujian) !!}</h4>
      <div>
        <div class="row">
          <table class="table">
            <tr>
              <td>
                <table class="table table-info">
                  <tr>
                    <td>MATA PELAJARAN</td>
                    <td align="center" style="width: 15px">:</td>
                    <th>{{ $mapel }}</th>
                  </tr>
                  <tr>
                    <td>KELAS</td>
                    <td align="center">:</td>
                    <th>{{ $kelas }}</th>
                  </tr>
                  <tr>
                    <td>JUMLAH PESERTA</td>
                    <td align="center">:</td>
                    <th>{{ count(json_decode($jadwal->peserta)).' Orang' }}</th>
                  </tr>
                </table>
              </td>
              <td align="right">
                <table class="table table-info" style="width: auto">
                  <tr>
                    <td align="left">JENIS SOAL</td>
                    <td align="center">:</td>
                    <th align="left">{{ $jadwal->jenis_soal?'Pilihan Ganda':'Essay' }}</th>
                  </tr>
                  <tr>
                    <td align="left">JUMLAH SOAL</td>
                    <td align="center">:</td>
                    <th align="left">{{ $jadwal->jumlah_soal }}</th>
                  </tr>
                  <tr>
                    <td align="left">BOBOT</td>
                    <td align="center">:</td>
                    <th align="left">{{ $jadwal->bobot }}</th>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
        <div class="row" style="margin-top: -30px">
          <table class="table table-bordered table-nilai">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">No. Ujian</th>
                <th class="text-center">Nama Siswa</th>
                <th class="text-center">Kelas</th>
                <th class="text-center">Jumlah Soal</th>
                <th class="text-center">Benar</th>
                <th class="text-center">Salah</th>
                <th class="text-center">Nilai Akhir</th>
              </tr>
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
        <div class="row" style="margin-bottom: 45px;margin-top: 30px;page-break-inside: avoid !important;">
          <div class="col-sm-12">
            <table class="table">
              <tr>
                <td></td>
                <td valign="top" width="200">{{ $sekolah->kota.', '.date('d',$mulai).' '.$helper->bulan(date('m',$mulai)).' '.date('Y',$mulai) }}</td>
              </tr>
              <tr>
                <td valign="top">Mengetahui</td>
                <td></td>
              </tr>
              <tr>
                <td valign="top" height="115">Kepala {{ $sekolah->nama }}</td>
                <td valign="top">Guru Mata Pelajaran</td>
              </tr>
              <tr>
                <td>
                  [.......................................................]
                </td>
                <td>
                  [.......................................................]
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
