<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    @include('print-style')
    <style>
    html,body{
      background: none;
    }
    .card{
      border-collapse: collapse;
      border: solid 1px #000;
      page-break-inside: avoid !important;
      font-size: 0.6em !important;
      width: 100%;
    }
    .no-border{
      border: none !important;
    }
    .detail{
      width: 100%;
    }
    .detail .info{
      text-transform: uppercase;
    }
    @page{
      margin: 20px;
    }
    </style>
  </head>
  <body>
    <table class="table" cellpadding="0" cellspacing="0">
      @php ($i = 1)
      @foreach ($peserta as $key => $p)
        @if ($i == 3)
          @php ($i = 1)
          </tr>
        @endif
        @if ($i == 1)
          <tr>
        @endif
        @php ($i++)
          <td style="padding: 5px">
            <table class="card" border="1">
              <tr class="text-center">
                <td style="vertical-align: middle;padding: 3px;width: 75px;border-right: none !important;text-align: center">
                  @if (is_file(base_path('uploads/'.$sekolah->dept_logo)))
                    <img src="{{ url('uploads/'.$sekolah->dept_logo) }}" style="height: 51px" alt="">
                  @elseif (is_file(base_path('uploads/'.$sekolah->logo)))
                    <img src="{{ url('uploads/'.$sekolah->logo) }}" style="height: 51px" alt="">
                  @endif
                </td>
                <td class="text-center" style="text-transform: uppercase;font-weight: bold;vertical-align: middle;padding: 3px;font-size: 1.3em;border-left: none;border-right: none">
                  {!! $sekolah->kop_kartu?nl2br($sekolah->kop_kartu).'<br />':'' !!}
                  {!! $sekolah->nama?$sekolah->nama:'' !!}
                </td>
                <td style="vertical-align: middle;padding: 3px;width: 75px;border-left: 0;text-align: center">
                  @if (is_file(base_path('uploads/'.$sekolah->dept_logo))&&is_file(base_path('uploads/'.$sekolah->logo)))
                    <img src="{{ url('uploads/'.$sekolah->logo) }}" style="height: 51px" alt="">
                  @endif
                </td>
              </tr>
              <tr>
                <td colspan="3" class="text-center" style="font-size: 0.9em;padding: 3px">
                  {!! nl2br($sekolah->alamat) !!}
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
                          <img src="{{ url('uploads/'.$p->photo) }}" style="width: 50px;height: 65px;padding: 5px 0 10px 0;object-fit: cover" alt="">
                        @else
                          <img src="{{ url('assets/img/nophoto.jpg') }}" style="width: 50px;height: 65px;padding: 5px 0 10px 0;object-fit: cover" alt="">
                        @endif
                      </td>
                      <td class="text-center" style="padding-bottom: 5px;padding-left: 100px;padding-top: 11px">
                        {{ date('d') }}&nbsp;{{ $helper->bulan(date('m')).' '.date('Y') }}
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
          </td>
        @endforeach
    </table>
  </body>
</html>
