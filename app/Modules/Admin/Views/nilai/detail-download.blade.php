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
      .soal img{
        max-width: 557px !important;
      }
      .jawaban img{
        vertical-align: top;
      }
      table.table-nilai th,table.table-nilai td{
        padding: 3px 7px;
        border-color: #000;
      }
      table.table-choice td{
        border: none !important;
        vertical-align: top;
      }
      table.table-nilai th{
        white-space: nowrap !important;
      }
      table.table-nilai td{
        vertical-align: top;
      }
      table.table-nilai td > *{
        color: inherit !important;
      }
      table.table-status th, table.table-status td{
        padding: 3px 7px;
      }
      @page{
        margin: 60px 50px;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <h4 class="text-center" style="padding:0;margin: 0">NILAI HASIL UJIAN</h4>
      <h4 class="text-center" style="padding:0;margin: 0;text-transform: uppercase">{!! nl2br($jadwal->nama_ujian) !!}</h4>
      <div style="margin-top: 30px">
        <div class="row">
          <table class="table">
            <tr>
              <td valign="top">
                <table class="table table-info">
                  <tr>
                    <td width="150">NOMOR UJIAN</td>
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
              </td>
              <td valign="top" width="350">
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
                      <th colspan="3" style="vertical-align: top !important;border: none"><h1 style="position: relative;font-size: 3em"><span style="font-size: 19px; position: relative;top: -35px;">NILAI AKHIR: </span>{{ $nilai }}</h1></th>
                    </tr>
                  @endif
                </table>
              </td>
            </tr>
          </table>
        </div>
        <div class="row" style="margin-top: -30px">
          @if ($jadwal->jenis_soal=='P')
            @include('Admin::nilai.detail.p')
          @else
            @include('Admin::nilai.detail.e')
          @endif
        </div>
        <div class="row" style="margin-bottom: 45px;page-break-inside: avoid !important;">
          <table class="table">
            <tr>
              <td width="475">
                <table class="table table-status">
                  <tr>
                    <th colspan="3">Keterangan</th>
                  </tr>
                  @php
                    $terjawab = App\Models\Tes::where('noujian',$siswa->noujian)->where('pin',$siswa->attemptLogin()->where('pin',$jadwal->pin)->first()->pin)->whereIn('soal_item',json_decode($siswa->attemptLogin()->where('pin',$jadwal->pin)->first()->soal_ujian??'[]'))->whereNotNull('jawaban')->count();
                  @endphp
                  <tr>
                    <td width="200">Terjawab</td>
                    <td>: {{ $terjawab }}</td>
                  </tr>
                  <tr>
                    <td>Tidak Dijawab</td>
                    <td>: {{ (count(json_decode($siswa->attemptLogin()->where('pin',$jadwal->pin)->first()->soal_ujian??'[]')))-$terjawab }}</td>
                  </tr>
                  @if ($jadwal->jenis_soal == 'P')
                    <tr>
                      <td>Jawaban Benar</td>
                      <td>: {{ $benar }}</td>
                    </tr>
                    <tr>
                      <td>Jawaban Salah</td>
                      <td>: {{ $jadwal->jumlah_soal-$benar }}</td>
                    </tr>
                  @endif
                </table>
              </td>
              <td>
                <table class="table">
                  <tr>
                    <td valign="top">{{ $sekolah->kota.', '.date('d').' '.$helper->bulan(date('m')).' '.date('Y') }}</td>
                  </tr>
                  <tr>
                    <td valign="top" height="100">Guru Mata Pelajaran</td>
                  </tr>
                  <tr>
                    <td valign="top">[.........................................................]</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
