@if (@$sekolah->kop_type=='atas')
  <table style="width: 100%">
    <tr>
      <td align="center">
        @if (is_file(base_path('uploads/'.@$sekolah->dept_logo))||is_file(base_path('uploads/'.@$sekolah->logo)))
          <img src="{{ url('uploads/'.$sekolah->dept_logo??$sekolah->logo) }}" alt="" style="display: inline;max-height: 75px"></td>
        @endif
    </tr>
    <tr>
      <td align="center">
        @php
        if ($sekolah->kop_kartu) {
          $kop = explode("\n",$sekolah->kop_kartu);
          if (count($kop)) {
            foreach ($kop as $key => $k) {
              printf('<h3 class="font-weight-bold" style="margin: 0;padding: 0 115px;font-size: 1.3em">%s</h3>',$k);
            }
          }
        }
        @endphp
      </td>
    </tr>
    <tr>
      <td align="center">
        <h3 class="font-weight-bold" style="margin: 0;padding: 0 115px">{{ $sekolah->nama }}</h3>
      </td>
    </tr>
    <tr>
      <td align="center">
        <em>
          {{ $sekolah->alamat??'' }}{!! $sekolah->telp?',&nbsp;<span style="white-space: nowrap"><i class="fa fa-fw fa-phone"></i>'.$sekolah->telp.'</span>':'' !!}{!! $sekolah->kodepos?',&nbsp;<span style="white-space: nowrap"><i class="fa fa-fw fa-envelope"></i>'.$sekolah->kodepos.'</span>':'' !!}
        </em>
      </td>
    </tr>
  </table>
@elseif (@$sekolah->kop_type=='samping')
  <table style="margin: 0 auto">
    <tr>
      <td rowspan="3" width="55" style="padding: 3px;vertical-align: middle !important">
        @if (is_file(base_path('uploads/'.@$sekolah->dept_logo))||is_file(base_path('uploads/'.@$sekolah->logo)))
          <img src="{{ url('uploads/'.$sekolah->dept_logo??$sekolah->logo) }}" alt="" style="display: inline;max-height: 95px"></td>
        @endif
      <td align="center">
        @php
        if ($sekolah->kop_kartu) {
          $kop = explode("\n",$sekolah->kop_kartu);
          if (count($kop)) {
            foreach ($kop as $key => $k) {
              printf('<h3 class="font-weight-bold" style="margin: 0;padding: 0 115px;font-size: 1.3em">%s</h3>',$k);
            }
          }
        }
        @endphp
      </td>
      <td style="padding: 3px;vertical-align: middle !important" rowspan="3" width="55">
        @if (is_file(base_path('uploads/'.@$sekolah->logo)))
          <img src="{{ url('uploads/'.@$sekolah->logo) }}" alt="" style="display: inline;max-height: 95px">
        @endif
      </td>
    </tr>
    <tr>
      <td align="center">
        <h3 class="font-weight-bold" style="margin: 0;padding: 0 115px">{{ $sekolah->nama }}</h3>
      </td>
    </tr>
    <tr>
      <td align="center">
        <em>
          {{ $sekolah->alamat??'' }}{!! $sekolah->telp?',&nbsp;<span style="white-space: nowrap"><i class="fa fa-fw fa-phone"></i>'.$sekolah->telp.'</span>':'' !!}{!! $sekolah->kodepos?',&nbsp;<span style="white-space: nowrap"><i class="fa fa-fw fa-envelope"></i>'.$sekolah->kodepos.'</span>':'' !!}
        </em>
      </td>
    </tr>
  </table>
@endif
<div style="border-top: solid 3px #000;border-bottom: solid 1px #000;margin-top: 3px;margin-bottom: 10px;padding: 1px 0;"></div>
<div class="clearfix"></div>
