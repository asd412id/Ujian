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
      table.table td{
        border: none !important;
      }
      table.table-info td{
        padding: 3px !important;
      }
      @page{
        margin: 20px 40px;
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
      <h4 class="text-center" style="padding:0;margin: 0">BERITA ACARA</h4>
      <h4 class="text-center" style="padding:0;margin: 0;margin-bottom: 25px;text-transform: uppercase">PELAKSANAAN {!! nl2br($jadwal->nama_ujian) !!}</h4>
      <div style="font-size: 1.2em !important">
        <div class="row">
          <div class="col-sm-12">
            <p>
              Pada hari ini <strong><em>{{ $helper->hari(date('D',$mulai)) }}</em></strong>
              tanggal <strong><em>{{ date('d',$mulai) }}</em></strong>
              bulan <strong><em>{{ $helper->bulan(date('m',$mulai)) }}</em></strong>
              tahun <strong><em>{{ date('Y',$mulai) }}</em></strong>
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-info">
              <tr>
                <td width="15" valign="top">a)</td>
                <td colspan="3">
                  Telah dilaksanakan {{ $jadwal->nama_ujian }} dari pukul <strong>.............</strong> sampai dengan pukul <strong>.............</strong>
                </td>
              </tr>
              <tr>
                <td></td>
                <td style="width: 250px;white-space: nowrap">Mata Pelajaran</td>
                <td style="width: 3px !important">:</td>
                <td style="font-weight: bold">{{ $mapel }}</td>
              </tr>
              <tr>
                <td></td>
                <td style="width: 250px">Kelas</td>
                <td style="width: 3px !important">:</td>
                <td style="font-weight: bold">{{ $kelas }}</td>
              </tr>
              <tr>
                <td></td>
                <td style="width: 250px">Jumlah Peserta</td>
                <td style="width: 3px !important">:</td>
                <td style="font-weight: bold">{{ count(json_decode($jadwal->peserta)).' Orang' }}</td>
              </tr>
              <tr>
                <td></td>
                <td style="width: 250px">Yang Hadir</td>
                <td style="width: 3px !important">:</td>
                <td style="font-weight: bold"> .......... Orang</td>
              </tr>
              <tr>
                <td></td>
                <td style="width: 250px;height: 30px" valign="top">Yang Tidak Hadir</td>
                <td style="width: 3px !important" valign="top">:</td>
                <td style="font-weight: bold" valign="top"> .......... Orang</td>
              </tr>
              <tr>
                <td valign="top">b)</td>
                <td colspan="3" style="padding-top: 15px !important">
                  Catatan Selama Pelaksanaan Ujian:
                </td>
              </tr>
              <tr>
                <td></td>
                <td colspan="3" style="border-bottom: solid 1px !important;padding: 10px !important">&nbsp;</td>
              </tr>
              <tr>
                <td></td>
                <td colspan="3" style="border-bottom: solid 1px !important;padding: 10px !important">&nbsp;</td>
              </tr>
              <tr>
                <td></td>
                <td colspan="3" style="border-bottom: solid 1px !important;padding: 10px !important">&nbsp;</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12" style="padding: 10px 35px">
            <p>Demikian Berita Acara ini dibuat dengan sesungguhnya</p>
          </div>
        </div>
        <div class="row" style="margin-bottom: 45px;margin-top: 30px;page-break-inside: avoid !important;">
          <div class="col-sm-12">
            <div style="white-space: nowrap;padding-left: 475px">
              <p>{{ '................, ...................... '.date('Y',$mulai) }}</p>
              <p>Yang Membuat Berita Acara,</p>
              <p style="margin-bottom: 125px">Pengawas</p>
              <p>[......................................................]</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
