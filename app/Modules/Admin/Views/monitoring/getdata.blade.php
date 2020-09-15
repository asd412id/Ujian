<table class="table table-hover">
  <thead class=" text-primary">
    <th>No</th>
    <th>No. Ujian</th>
    <th>Nama</th>
    <th>Status</th>
    <th>Mulai</th>
    <th>Sisa Waktu</th>
    @if ($jadwalUjian->jenis_soal == 'P')
      <th>Nilai</th>
      <th>Status</th>
    @endif
    <th></th>
  </thead>
  <tbody class="d-peserta">
    @php
    $belumLogin = $jadwalUjian->siswa_not_login;
    @endphp
    @if (count($data))
      @foreach ($data as $key => $v)
        @php
        $timer = null;
        $distance = -1;
        $h = '-';
        $m = '-';
        $s = '-';
        if ($v->start) {
          $jadwal = $v->jadwal;
          $timerNow = Carbon\Carbon::now()->addMinutes($jadwal->lama_ujian) <= Carbon\Carbon::parse($jadwal->selesai_ujian) ? Carbon\Carbon::now()->addMinutes($jadwal->lama_ujian) : Carbon\Carbon::parse($jadwal->selesai_ujian);

          if (is_null($v->end) && !is_null($v->_token)) {
            $intval = $timerNow->diffInSeconds($v->created_at->addMinutes($jadwal->lama_ujian));
          }else {
            $intval = $timerNow->diffInSeconds(Carbon\Carbon::now()->subSeconds($v->created_at->diffInSeconds($v->updated_at,false))->addMinutes($jadwal->lama_ujian));
          }


          $timer = $timerNow->subSeconds($intval);

          $distance = Carbon\Carbon::now()->diffInSeconds($timer,false);

          if ($distance <= 0) {
            $v->end = Carbon\Carbon::now();
            $v->save();
          }

          $hours = floor(($distance % (60 * 60 * 24)) / (60 * 60));
          $minutes = floor(($distance % (60 * 60)) / 60);
          $seconds = floor($distance % 60)+1;

          $h = $hours;
          $m = $minutes;
          $s = $seconds;

          if ($hours<10) {
            $h = '0'.$hours;
          }
          if ($minutes<10) {
            $m = '0'.$minutes;
          }
          if ($seconds<10) {
            $s = '0'.$seconds;
          }

          if ($distance<0) {
            $h = '00';
            $m = '00';
            $s = '00';
          }

        }

        if ($jadwalUjian->jenis_soal == 'P') {
          $jadwal = $v->jadwal;
          $nilai = 0;
          $nbenar = 0;
          $siswa = $v->siswa;
          $plogin = $siswa->attemptLogin()->where('pin',$jadwal->pin)->first();
          $jumlah_soal = @count(json_decode($plogin->soal_ujian));
          $dtes = App\Models\Tes::where('noujian',$siswa->noujian)
          ->where('pin',$jadwal->pin)->whereNotNull('jawaban')->whereIn('soal_item',json_decode($plogin->soal_ujian??'[]'))->get();
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
          <td>{{ $key+1 }}</td>
          <td>{{ $v->siswa->noujian??'-' }}</td>
          <td>{{ $v->siswa->nama??'-' }}</td>
          <td>
            @if ($v->end)
              <span class="text-success" style="font-weight: bold">Selesai</span>
            @elseif ($v->start)
              <span class="text-info" style="font-weight: bold">Mengerjakan soal</span>
            @elseif (is_null($v->_token))
              <span class="text-danger" style="font-weight: bold">Belum Login</span>
            @else
              <span class="text-primary" style="font-weight: bold">Mengecek data</span>
            @endif
          </td>
          <td>{{ $v->start?date('d/m/Y H:i',strtotime($v->start)):'-' }}</td>
          <td {{ $timer&&!$v->end?'class=l-time':'' }} data-timer="{{ $timer }}">
            @if (@$distance <= 0 && $v->start)
              <span class="text-danger">Waktu Habis</span>
            @else
              <span class="{{ ($v->start&&$hours==0&&$minutes<10?'text-warning':'') }}">{{ $h.':'.$m.':'.$s }}</span>
            @endif
          </td>
          @if ($jadwalUjian->jenis_soal == 'P')
            <td><strong>{{ $nilai }}</strong></td>
            <td>
              <span class="badge badge-primary">Soal Dikerjakan: {{ $dtes->count().'/'.$jumlah_soal }}</span><br>
              <span class="badge badge-info">Soal Sekarang: {{ @$v->start?$v->current_number+1:'-' }}</span><br>
              <span class="badge badge-success">Benar: {{ $nbenar }}</span>
              <span class="badge badge-danger">Salah: {{ $dtes->count()-$nbenar }}</span>
            </td>
          @endif
          <td style="white-space: nowrap;width: 50px" class="text-right">
            <a href="javascript:void(0)" class="btn btn-sm btn-xs btn-success stop" title="Reset Login" data-text="Reset Login {{ $v->siswa->nama }}?" data-url="{{ route('jadwal.ujian.reset',['pin'=>$v->pin,'noujian'=>$v->noujian]) }}" class="text-info"><i class="material-icons">refresh</i></a>
            @if (!is_null($v->_token))
              @if ($v->start || $v->end)
                <a href="javascript:void(0)" class="btn btn-sm btn-xs btn-warning stop" title="Reset Waktu" data-text="Semua jawaban akan terhapus!<br>Reset Waktu {{ $v->siswa->nama }}?" data-url="{{ route('jadwal.ujian.restart',['pin'=>$v->pin,'noujian'=>$v->noujian]) }}" class="text-info"><i class="material-icons">undo</i></a>
              @endif
              @if (!$v->end)
                <a href="javascript:void(0)" class="btn btn-sm btn-xs btn-danger stop" title="Set Selesai" data-text="Set Selesai {{ $v->siswa->nama }}?" data-url="{{ route('jadwal.ujian.stop',['pin'=>$v->pin,'noujian'=>$v->noujian]) }}" class="text-info"><i class="material-icons">not_interested</i></a>
              @endif
            @endif
          </td>
        </tr>
      @endforeach
      @if (count($belumLogin))
        @foreach ($belumLogin as $key1 => $v)
          <tr>
            <td>{{ $key1+1 }}</td>
            <td>{{ $v->noujian??'-' }}</td>
            <td>{{ $v->nama??'-' }}</td>
            <td>
                <span class="text-danger" style="font-weight: bold">Belum Login</span>
            </td>
            @php
              $start = @$v->attemptLogin()->where('pin',$jadwalUjian->pin)->first()->start??null;
            @endphp
            <td>{{ $start?date('d/m/Y H:i',strtotime($start)):'-' }}</td>
            <td>-:-:-</td>
            @if ($jadwalUjian->jenis_soal == 'P')
              <td>-</td>
              <td>-</td>
            @endif
            <td style="white-space: nowrap;width: 50px" class="text-right"></td>
          </tr>
        @endforeach
      @endif
    @else
      @if (count($belumLogin))
        @foreach ($belumLogin as $key1 => $v)
          <tr>
            <td>{{ $key1+1 }}</td>
            <td>{{ $v->noujian??'-' }}</td>
            <td>{{ $v->nama??'-' }}</td>
            <td>
                <span class="text-danger" style="font-weight: bold">Belum Login</span>
            </td>
            @php
              $start = @$v->attemptLogin()->where('pin',$jadwalUjian->pin)->first()->start??null;
            @endphp
            <td>{{ $start?date('d/m/Y H:i',strtotime($start)):'-' }}</td>
            <td>-:-:-</td>
            @if ($jadwalUjian->jenis_soal == 'P')
              <td>-</td>
              <td>-</td>
            @endif
            <td style="white-space: nowrap;width: 50px" class="text-right"></td>
          </tr>
        @endforeach
      @else
        <tr>
          <td class="text-center no-data" colspan="9">Data tidak tersedia</td>
        </tr>
      @endif
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
