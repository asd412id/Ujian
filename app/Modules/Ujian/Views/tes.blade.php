@extends('Ujian::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary soal-title">
        <h4 class="card-title">
          <span class="timer" style="font-weight: bold;line-height: 2em">00:00:00</span>
          <span class="pull-right">
            <a href="{{ route('ujian.selesai') }}" class="btn btn-sm btn-danger" onclick="return confirm($(this).data('text'))" data-text="Ujian akan selesai dan jawaban tidak dapat diubah kembali! ANDA YAKIN?" data-url="{{ route('ujian.selesai') }}">Selesai Ujian</a>
          </span>
        </h4>
      </div>
      <div class="card-body">
        <div class="col-md-12" id="soal-wrapper"></div>
        <div class="mask-container">
          <div class="mask text-center"><div class="text-center col-md-12">
            <img src="{{ url('assets/img/loader.gif') }}" alt="">
          </div></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
<script>
var countDownDate = new Date("{{ $timer }}").getTime();
var now = new Date('{{ $now }}');
var checkingReq = null;
var ar = true;
var time = 0;
var loadProcess = false;
timer(countDownDate,now);

function timer(countDownDate,now) {
  var x = setInterval(function() {

    time++;

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
      $(".timer").addClass('text-warning');
    }

    $(".timer").html(hours + ":" + minutes + ":" + seconds);

    if (time==15) {
      if (ar) {
        checkingReq = $.ajax({
          url: '{{ route('ujian.getsoal') }}',
          data: {'checking': 'status'},
          success: function(res){
            if (res === 'error') {
              location.href = '{{ route('ujian.selesai') }}';
            }else {
              time=0;
              clearInterval(x);
              countDownDate = new Date(res.timer).getTime();
              now = new Date(res.now);
              timer(countDownDate,now);
            }
          }
        });
      }
    }

    if (distance < 0) {
      clearInterval(x);
      $(".timer").html("Waktu Ujian Selesai");
      location.href = '{{ route('ujian.selesai') }}';
    }
  }, 1000);
}

$(".btn-soal").click(function(){
  var _btn = $(this);
  var soal = _btn.data('soal');
  var key = _btn.data('key');
  getSoal(soal,key,_btn);
})

function getSoal(soal,key,btn) {
  ar = false;
  if (checkingReq != null) {
    checkingReq.abort();
  }
  $(".mask-container").show();
  if (loadProcess == false) {
    loadProcess = true;
    $.ajax({
      url: '{{ route('ujian.getsoal') }}',
      data: {'soal': soal,'key': key},
      success: function(res){
        if (res === 'error') {
          location.href = '{{ route('ujian.selesai') }}';
        }else {
          ar = true;
          time = 0;
          loadProcess = false;
          $('html, body').animate({
            scrollTop: $(".content").offset().top
          }, 500);
          $('.main-panel').animate({
            scrollTop: $(".content").offset().top
          }, 500);
          $(".mask-container").hide();
          $("#soal-wrapper").html(res);
          $(".btn-soal").closest('li').removeClass('active');
          btn.closest('li').addClass('active');
          if ($(".audio-play").length) {
            $(".audio-play").each(function(i,v){
              var a = $(this);
              var pl = a.data('play');
              if (pl>0) {
                var ad = '';
                if (a.parent().data('count')>=pl) {
                  ad = 'disabled';
                }
                a.parent().append('<button type="button" class="btn btn-success btn-play" data-play="'+i+'" '+ad+'><i class="material-icons">play_arrow</i>&nbsp;&nbsp;Audio '+(i+1)+'</button>');
                a.css('display','none');
                setPlay(a,pl);
              }
            })
          }
        }
      }
    });
  }
}

function setPlay(a,pl) {
  $(".btn-play").click(function(){
    $('audio')[$(this).data('play')].play();
  })
  var i = a.parent().data('count');
  a.on('ended',function(e){
    i++;
    $.get('{{ route('ujian.audiorepeat') }}',{
      soal: a.parent().data('soal'),
      audio: a.parent().data('item')+a.parent().data('index'),
      count: i
    },function(res){
      if (res>=pl) {
        a.parent().data('count',res);
        a.parent().find('.btn-play').prop('disabled',true);
        a.remove();
      }
    });
  })
}

$(document).ready(function(){
  $(".btn-soal:eq({{ $siswa->login->current_number }})").click();
})

</script>
@endsection
