<table class="table table-hover">
  <thead class=" text-primary">
    <th>No</th>
    <th>No. Ujian</th>
    <th>Nama</th>
    <th>Status</th>
    <th>Mulai</th>
    <th>Sisa Waktu</th>
    <th>Alamat IP</th>
    <th></th>
  </thead>
  <tbody>
    @if (count($data))
      @foreach ($data as $key => $v)
        @php
        $timer = null;
        $distance = -1;
        $h = '00';
        $m = '00';
        $s = '00';
        if ($v->start) {
          $jadwal = $v->jadwal;
          $timerNow = Carbon\Carbon::now()->addMinutes($jadwal->lama_ujian) <= Carbon\Carbon::parse($jadwal->selesai_ujian) ? Carbon\Carbon::now()->addMinutes($jadwal->lama_ujian) : Carbon\Carbon::parse($jadwal->selesai_ujian);

          $intval = $timerNow->diffInSeconds(Carbon\Carbon::parse($v->start)->addMinutes($jadwal->lama_ujian));

          $timer = $timerNow->subSeconds($intval);

          $distance = Carbon\Carbon::now()->diffInSeconds($timer,false);

          $h = floor(($distance % (60 * 60 * 24)) / (60 * 60));
          $m = floor(($distance % (60 * 60)) / 60);
          $s = floor($distance % 60)+1;

          if ($h<10) {
            $h = '0'.$h;
          }
          if ($m<10) {
            $m = '0'.$m;
          }
          if ($s<10) {
            $s = '0'.$s;
          }

          if ($distance<0) {
            $h = '00';
            $m = '00';
            $s = '00';
          }

        }
        @endphp
        <tr>
          @php
          $index = Request::get('page')??1;
          @endphp
          <td>{{ (($index-1)*10)+$key+1 }}</td>
          <td>{{ $v->siswa->noujian??'-' }}</td>
          <td>{{ $v->siswa->nama??'-' }}</td>
          <td>
            @if ($v->end)
              <span class="text-success" style="font-weight: bold">Selesai</span>
            @elseif ($v->start)
              <span class="text-info" style="font-weight: bold">Mengerjakan soal</span>
            @else
              <span class="text-primary" style="font-weight: bold">Mengecek data</span>
            @endif
          </td>
          <td>{{ $v->start?date('d/m/Y H:i',strtotime($v->start)):'-' }}</td>
          <td {{ $timer&&!$v->end?'class=l-time':'' }} data-timer="{{ $timer }}">
            @if ($v->end || (@$distance <= 0 && $v->start))
              Waktu Habis
            @else
              {{ $h.':'.$m.':'.$s }}
            @endif
          </td>
          <td>{{ $v->ip_address }}</td>
          <td style="white-space: nowrap;width: 50px" class="text-right">
            <a href="javascript:void(0)" class="btn btn-sm btn-xs btn-warning stop" title="Reset Login" data-text="Reset Login {{ $v->siswa->nama }}?" data-url="{{ route('jadwal.ujian.reset',['pin'=>$v->pin,'noujian'=>$v->noujian]) }}" class="text-info"><i class="material-icons">refresh</i></a>
            @if (!$v->end)
              <a href="javascript:void(0)" class="btn btn-sm btn-xs btn-danger stop" title="Set Selesai" data-text="Set Selesai {{ $v->siswa->nama }}?" data-url="{{ route('jadwal.ujian.stop',['pin'=>$v->pin,'noujian'=>$v->noujian]) }}" class="text-info"><i class="material-icons">not_interested</i></a>
            @endif
          </td>
        </tr>
      @endforeach
    @else
      <tr>
        <td class="text-center no-data" colspan="7">Data tidak tersedia</td>
      </tr>
    @endif
  </tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
  $(".l-time").each(function(){
    var countDownDate = new Date($(this).data('timer')).getTime();
    var now = new Date('{{ Carbon\Carbon::now() }}');
    timer($(this),countDownDate,now)
  })
  $(".stop").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    stopUjian($(this));
  })
})
</script>
