@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Status Ujian {{ $jadwal->nama_ujian }}</h4>
          <p class="card-category">Kelas: <strong>{{ $kelas }}</strong></p>
          <p class="card-category">Mata Pelajaran: <strong>{{ $mapel }}</strong></p>
          <p class="card-category">PIN: <strong>{{ $jadwal->pin }}</strong></p>
        </div>
      </div>
      <div class="card-body">
        <div class="text-right">
          <input type="text" class="form-control" placeholder="Cari Peserta" id="cari-peserta">
        </div>
        <div class="table-responsive" id="data-wrapper">
          <table class="table table-hover">
            <thead class=" text-primary">
              <th>No</th>
              <th>No. Ujian</th>
              <th>Nama</th>
              <th>Status</th>
              <th>Mulai</th>
              <th>Sisa Waktu</th>
              <th>Nilai</th>
              <th>Status</th>
              <th></th>
            </thead>
            <tbody class="d-peserta">
              @if (count($login))
                @foreach ($login as $key => $v)
                  @php
                  $timer = null;
                  $jadwal = $v->jadwal;
                  if ($v->start) {
                    $timerNow = Carbon\Carbon::now()->addMinutes($jadwal->lama_ujian) <= Carbon\Carbon::parse($jadwal->selesai_ujian) ? Carbon\Carbon::now()->addMinutes($jadwal->lama_ujian) : Carbon\Carbon::parse($jadwal->selesai_ujian);

                    $intval = $timerNow->diffInSeconds(Carbon\Carbon::parse($v->start)->addMinutes($jadwal->lama_ujian));

                    $timer = $timerNow->subSeconds($intval);
                  }

                  $nilai = 0;
                  $nbenar = 0;
                  $siswa = $v->siswa;
                  $plogin = $siswa->attemptLogin()->where('pin',$jadwal->pin)->first();
                  $jumlah_soal = @count(json_decode($plogin->soal_ujian));
                  $dtes = App\Models\Tes::where('noujian',$siswa->noujian)
                  ->where('pin',$jadwal->pin)->whereIn('soal_item',json_decode($plogin->soal_ujian??'[]'))->get();
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
                      @else
                        <span class="text-primary" style="font-weight: bold">Mengecek data</span>
                      @endif
                    </td>
                    <td>{{ $v->start?date('d/m/Y H:i',strtotime($v->start)):'-' }}</td>
                    <td {{ $timer?'class=l-time':'' }} data-timer="{{ $timer }}">00:00:00</td>
                    <td><strong>{{ $nilai }}</strong></td>
                    <td>
                      <span class="badge badge-primary">Soal Dikerjakan: {{ $dtes->count().'/'.$jumlah_soal }}</span><br>
                      <span class="badge badge-success">Benar: {{ $nbenar }}</span>
                      <span class="badge badge-danger">Salah: {{ $jumlah_soal-$nbenar }}</span>
                    </td>
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
                  <td class="text-center no-data">Data tidak tersedia</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
function loader() {
  var tm;
  $.get('{{ route('jadwal.ujian.monitoring.getdata',['uuid'=>$jadwal->uuid]) }}',{},function(res){
    if (res) {
      $("#data-wrapper").html(res);
      cariPeserta($("#cari-peserta").val());
      tm = setTimeout(()=>{
        loader()
      },10000)
    }else {
      clearTimeout(tm);
      console.log('error fetch data');
    }
  });
}
loader()

function cariPeserta(val) {
  var dp = $(".d-peserta");
  if (val != '') {
    dp.find('tr').hide();
    dp.find("td:contains('"+val+"')").closest('tr').show();
  }else{
    dp.find('tr').show();
  }
  $.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function( elem ) {
      return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
  });
}

$(document).ready(function(){
  $("#search-bar").hide();
  $("#cari-peserta").focus();
  $(".l-time").each(function(){
    var countDownDate = new Date($(this).data('timer')).getTime();
    var now = new Date('{{ Carbon\Carbon::now() }}');
    timer($(this),countDownDate,now)
  });
  $(".stop").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    stopUjian($(this));
  })
  $("#cari-peserta").on('change keyup',function(){
    cariPeserta($(this).val());
  });
})
function timer(el,countDownDate,now) {
  var x = setInterval(function() {

    now.setSeconds(now.getSeconds()+1);

    var intval = now.getTime();

    var distance = countDownDate - intval;

    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    if (hours<10) {
      hours = '0'+hours;
    }
    if (minutes<10) {
      minutes = '0'+minutes;
    }
    if (seconds<10) {
      seconds = '0'+seconds;
    }

    if (hours==0&&minutes<10) {
      $("#timer").addClass('text-warning');
    }

    el.html(hours + ":"
    + minutes + ":" + seconds);


    if (distance < 0) {
      clearInterval(x);
      el.html('Waktu Habis');
    }
  }, 1000);
}

function stopUjian(btn) {
  // var btn = $(btn);
  var text = btn.data('text');
  var url = btn.data('url');

  var confirm = `
    <h3 class="text-center">`+text+`</h3>
    <div class="text-center" style="margin-bottom: 15px">
      <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
      <a href="javascript:void(0)" data-url="`+url+`" class="btn btn-danger" id="dostop">Ya</a>
    </div>
  `;
  $(".modal-confirm").find('.modal-content').html(confirm);
  $(".modal-confirm").modal({
    show: true,
    backdrop: 'static'
  });

  $("#dostop").click(function(e){
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    $.get(url,{},function(stat){
      if (stat) {
        $(".modal-confirm").modal('hide');
        $.get('{{ route('jadwal.ujian.monitoring.getdata',['uuid'=>$jadwal->uuid]) }}',{},function(res){
          if (res) {
            $("#data-wrapper").html(res);
            cariPeserta($("#cari-peserta").val());
          }else {
            console.log('error fetch data');
          }
        });
      }
    })
  })

}
</script>
@endsection
