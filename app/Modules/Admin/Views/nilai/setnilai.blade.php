<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style media="screen">
      .text-center{
        text-align: center;
      }
      .text-left{
        text-align: left;
      }
      .text-right{
        text-align: right;
      }
      .item td{
        vertical-align: top;
      }
      table.soaljawab th,table.soaljawab td{
        padding: 7px;
      }
      .soaljawab,.soaljawab th,.soaljawab td{
        border: solid 1px #333;
      }
    </style>
  </head>
  <body>
    @foreach ($peserta as $key => $p)
      {{-- @if ($key>0)
        <div style="border: dashed 1px;margin: 5px 0"></div>
      @endif --}}
      <div style="page-break-inside: avoid;padding: 15px">
        <table class="id" style="font-weight: bold">
          <tr>
            <td>NO. UJIAN</td>
            <td width="10">:</td>
            <td>{{ strtoupper($p->noujian) }}</td>
          </tr>
          <tr>
            <td>NAMA</td>
            <td width="10">:</td>
            <td>{{ strtoupper($p->nama) }}</td>
          </tr>
          <tr>
            <td>KELAS</td>
            <td width="10">:</td>
            <td>{{ strtoupper($p->kelas->nama) }}</td>
          </tr>
          <tr>
            <td>JURUSAN</td>
            <td width="10">:</td>
            <td>{{ strtoupper($p->kelas->jurusan??'-') }}</td>
          </tr>
          <tr>
            <td>TINGKAT</td>
            <td width="10">:</td>
            <td>{{ strtoupper($p->kelas->tingkat??'-') }}</td>
          </tr>
        </table>
        <table style="width:100%;margin-top: 7px" class="soaljawab" cellspacing="0" cellpadding="0">
          <thead>
            <th>NO</th>
            <th>SOAL</th>
            <th>JAWABAN</th>
            <th>NILAI</th>
          </thead>
          <tbody>
            @foreach ($jadwal->getSoal->item as $key => $s)
              @php
              $jawaban = '';
              if (@$p->tes()->where('soal_item',$s->uuid)->first()->jawaban) {
                $jawaban = @$p->tes()->where('soal_item',$s->uuid)->first()->jawaban;
              }
              @endphp
              <tr class="item">
                <td class="text-center">{{ $key+1 }}</td>
                <td style="width: 55%">{!! nl2br($s->soal) !!}</td>
                <td>{!! nl2br(e($jawaban)) !!}</td>
                <td style="width: 100px"></td>
              </tr>
            @endforeach
            <tr>
              <td colspan="3" class="text-center" style="font-weight: bold;height: 45px">NILAI AKHIR</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    @endforeach
  </body>
</html>
