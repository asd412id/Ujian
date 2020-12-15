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
        max-width: 450px !important;
      }
      table.choice td img{
        max-width: 80px !important;
      }
      table.choice td{
        vertical-align: top;
        text-align: left !important;
      }
      table.table-nilai th,table.table-nilai td{
        padding: 3px 7px;
        border: none !important;
      }
      table.choice td:nth-child(1){
        padding-left: 0 !important;
        padding-right: 0 !important;
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
      table tr, table th, table td{
        page-break-inside: avoid !important;
        page-break-after: always !important;
      }
      @page{
        margin: 20px 50px;
      }
    </style>
  </head>
  <body>
    @include('Admin::kop')
    <div class="container-fluid">
      <h4 class="text-center" style="padding:0;margin: 0;text-transform: uppercase">{!! nl2br($jadwal->nama_ujian) !!}</h4>
      <div style="margin-top: 10px">
        <div class="row">
          <table class="table">
            <tr>
              <td valign="top" width="325">
                <table class="table table-info" style="width: auto">
                  <tr>
                    <td>MATA PELAJARAN</td>
                    <td align="center" style="width: 15px">:</td>
                    <th>{{ $mapel }}</th>
                  </tr>
                  <tr>
                    <td>Kelas</td>
                    <td align="center" style="width: 15px">:</td>
                    <th>{{ $kelas }}</th>
                  </tr>
                </table>
              </td>
              <td valign="top" align="right">
                <table class="table table-info" style="width: auto">
                  <tr>
                    <td align="left">JUMLAH SOAL</td>
                    <td align="left">:</td>
                    <th align="left">{{ count($soal) }}</th>
                  </tr>
                  <tr>
                    <td align="left">ALOKASI WAKTU</td>
                    <td align="left">:</td>
                    <th align="left">{{ $jadwal->lama_ujian.' Menit' }}</th>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
        <div class="row" style="margin-top: -30px">
          @include('Admin::nilai.detail-soal.item')
        </div>
        <div class="row" style="margin-bottom: 45px;margin-top: 30px;page-break-inside: avoid !important;">
          <table class="table">
            <tr>
              <td style="text-align: right">
                <table class="table" style="width: auto">
                  <tr>
                    <td valign="top" align="left">{{ '................., ............................ '.date('Y') }}</td>
                  </tr>
                  <tr>
                    <td valign="top" height="100" align="left">Guru Mata Pelajaran</td>
                  </tr>
                  <tr>
                    <td valign="top" align="left">[.........................................................]</td>
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
