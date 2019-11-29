@if (is_file(base_path('uploads/'.$sekolah->dept_logo)))
  <div style="text-align: center;position: absolute;left: 0;top: 0;bottom: 15px">
    <img src="{{ url('uploads/'.$sekolah->dept_logo) }}" alt="" style="display: inline;height: 100%;max-height: 95px;">
  </div>
@endif
@php
  if ($sekolah->kop_kartu) {
    $kop = explode("\n",$sekolah->kop_kartu);
    if (count($kop)) {
      foreach ($kop as $key => $k) {
        printf('<h3 class="font-weight-bold" style="text-align: center;margin: 0">%s</h3>',$k);
      }
    }
  }
@endphp
<h3 class="font-weight-bold" style="text-align: center;margin: 0">{{ $sekolah->nama }}</h3>
@if (is_file(base_path('uploads/'.$sekolah->logo)))
  <div style="text-align: center;position: absolute;right: 0;top: 0;bottom: 15px">
    <img src="{{ url('uploads/'.$sekolah->logo) }}" alt="" style="display: inline;height: 100%;max-height: 95px;">
  </div>
@endif
<p style="text-align: center;margin-bottom: 0;margin-top: 5px;padding: 0 125px"><em>{{ $sekolah->alamat }},&nbsp;<i class="fa fa-fw fa-phone"></i> {{ $sekolah->telp }}</em>,&nbsp;<i class="fa fa-fw fa-envelope"></i> {{ $sekolah->fax }}</em></p>
<div style="border-top: solid 3px #000;border-bottom: solid 1px #000;margin-top: 3px;margin-bottom: 0;padding: 1px 0;"></div>
<div class="clearfix"></div>
