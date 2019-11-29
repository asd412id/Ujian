<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}">
    <style media="screen">
      html,body{
        background: none;
        margin: 0;
        padding: 0;
        font-size: 0.8em;
      }
      .card{
        width: 100%;
        border: solid 1px;
        border-collapse: collapse;
      }
      .detail{
        width: 100%;
        font-size: 1em;
      }
      .detail .info{
        text-transform: uppercase;
      }
      table, tr, td, th, tbody, thead, tfoot {
          page-break-inside: avoid !important;
      }
    </style>
  </head>
  <body>
    @foreach ($peserta as $key => $p)
      @if ($key % 2 == 0 && $key != 0)
        <div class="clearfix"></div>
      @endif
      <div class="col-xs-6" style="margin-top: 10px">
        <table class="card">
          <tr class="text-center">
            <td style="vertical-align: middle;padding: 3px;width: 75px">
              @if (is_file(base_path('uploads/'.$sekolah->dept_logo)))
                <img src="{{ url('uploads/'.$sekolah->dept_logo) }}" style="height: 53px" alt="">
              @elseif (is_file(base_path('uploads/'.$sekolah->logo)))
                <img src="{{ url('uploads/'.$sekolah->logo) }}" style="height: 53px" alt="">
              @endif
            </td>
            <td class="text-center" style="text-transform: uppercase;font-weight: bold;vertical-align: middle;padding: 3px;font-size: 1.3em">
              {!! $sekolah->kop_kartu?nl2br($sekolah->kop_kartu).'<br />':'' !!}
              {!! $sekolah->nama?$sekolah->nama:'' !!}
            </td>
            <td style="vertical-align: middle;padding: 3px;width: 75px">
              @if (is_file(base_path('uploads/'.$sekolah->dept_logo))&&is_file(base_path('uploads/'.$sekolah->logo)))
                <img src="{{ url('uploads/'.$sekolah->logo) }}" style="height: 53px" alt="">
              @endif
            </td>
          </tr>
          <tr>
            <td colspan="3" class="text-center" style="border: solid 1px;font-size: 0.9em;padding: 3px">
              {{ $sekolah->alamat.', '.$sekolah->kota.', '.$sekolah->propinsi }}
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <table class="detail">
                <tr>
                  <td colspan="3" class="text-center" style="font-weight: bold;font-size: 1.3em;padding-top: 5px;">KARTU UJIAN</td>
                </tr>
                <tr class="info">
                  <td style="width: 100px;padding-left: 15px;padding-top: 5px">NO. UJIAN</td>
                  <td style="width: 10px;padding-top: 5px">:</td>
                  <td style="font-weight:  bold;padding-top: 5px">{{ $p->noujian }}</td>
                </tr>
                <tr class="info">
                  <td style="width: 100px;padding-left: 15px">NAMA</td>
                  <td style="width: 10px">:</td>
                  <td style="font-weight:  bold">{{ $p->nama }}</td>
                </tr>
                <tr class="info">
                  <td style="width: 100px;padding-left: 15px">KELAS</td>
                  <td style="width: 10px">:</td>
                  <td style="font-weight:  bold">{{ $p->kelas->nama }}</td>
                </tr>
                <tr class="info">
                  <td style="width: 100px;padding-left: 15px">JURUSAN</td>
                  <td style="width: 10px">:</td>
                  <td style="font-weight:  bold">{{ $p->kelas->jurusan??'-' }}</td>
                </tr>
                <tr>
                  <td style="width: 100px;padding-left: 15px">PASSWORD</td>
                  <td style="width: 10px">:</td>
                  <td style="font-weight:  bold">{{ $p->real_password??'-' }}</td>
                </tr>
                <tr>
                  <td colspan="2" style="padding-left: 15px">
                    @if (is_file(base_path('uploads/'.$p->photo)))
                      <img src="{{ url('uploads/'.$p->photo) }}" style="width: 70px;height: auto;padding: 5px 0 10px 0;object-fit: cover" alt="">
                    @else
                      <img src="{{ url('assets/img/nophoto.jpg') }}" style="width: 70px;height: 106px;padding: 5px 0 10px 0;object-fit: cover" alt="">
                    @endif
                  </td>
                  <td class="text-center" style="padding-bottom: 5px;padding-left: 100px;padding-top: 11px">
                    {{ $sekolah->kota }},&nbsp;{{ date('d') }}&nbsp;{{ $helper->bulan(date('m')).' '.date('Y') }}
                    <br>
                    <br>
                    <br>
                    Panitia Ujian
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    @endforeach
  </body>
</html>
