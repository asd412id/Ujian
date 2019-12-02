<div class="soal-wrapper" style="font-size: 1.3em;padding: 15px 0">
  <form action="{{ route('ujian.submit',['uuid'=>$soal->uuid]) }}" id="form-ujian">
    <div class="text-center num">Soal {{ $key }}</div>
    <table style="width: 100%">
      <tr>
        <td class="s-wrap" style="vertical-align: top;padding-bottom: 15px">{!! nl2br($soal->soal) !!}</td>
      </tr>
    </table>
    <table style="width: 100%">
      @if ($soal->jenis_soal=='P'&&!is_null($opsis)&&$opsis!='null')
        @php
          $i=0;
        @endphp
        @foreach ($opsis as $key => $opsi)
          @php
            $range = range('A','Z');
          @endphp
          <tr class="otext" style="cursor: pointer">
            <td width="40" style="vertical-align: top;white-space: nowrap;padding: 5px 0">
              @php
                $select = App\Models\Tes::where('soal_item',$soal->uuid)
                ->where('noujian',$siswa->noujian)
                ->where('pin',$siswa->login->pin)
                ->first()->jawaban;
              @endphp
              <input type="radio" {{ !is_null($select)&&$select==$key?'checked':'' }} name="jawab" class="jawab" value="{{ $key }}">
              {{ $range[$i++].'.' }}
            </td>
            <td style="vertical-align: top;padding: 5px 0">{{ $opsi }}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="2">
            <h4 style="font-weight: bold">Jawaban Anda:</h4>
            <textarea name="jawab" rows="5" class="form-control" placeholder="Jawaban Anda...">{{ \Auth::guard('siswa')->user()->login->tes()->where('soal_item',$soal->uuid)->first()->jawaban }}</textarea>
          </td>
        </tr>
      @endif
    </table>
  </form>
</div>
<div class="button-wrap text-center">
@foreach (json_decode($allSoal) as $key => $s)
  @if ($s == $soal->uuid)
    @if (array_key_exists($key-1,json_decode($allSoal)))
      <a class="btn btn-sm btn-default btn-soal-nav btn-prev" data-btn="#soal-{{ json_decode($allSoal)[$key-1] }}" data-soal="{{ json_decode($allSoal)[$key-1] }}" data-key="{{ $key }}" href="javascript:void(0)">
        Soal Sebelumnya
      </a>
    @endif
    <button type="button" class="btn btn-success" id="btn-submit">Simpan & Lanjutkan</button>
    @if (array_key_exists($key+1,json_decode($allSoal)))
      <a class="btn btn-sm btn-default btn-soal-nav btn-next" data-btn="#soal-{{ json_decode($allSoal)[$key+1] }}" data-soal="{{ json_decode($allSoal)[$key+1] }}" data-key="{{ $key+2 }}" href="javascript:void(0)">
        Soal Berikutnya
      </a>
    @endif
  @endif
@endforeach
</div>
<script>
if ($("meta[name='csrf-token']").length) {
  $("meta[name='csrf-token']").prop('content','{{ csrf_token() }}');
}
$(".otext").click(function(){
  $(this).find('.jawab').prop('checked',true);
})
$(".btn-soal-nav").click(function(){
  var _btn = $(this);
  var soal = _btn.data('soal');
  var key = _btn.data('key');
  getSoal(soal,key,$(_btn.data('btn')));
})

$("#form-ujian").submit(function(e){
  e.preventDefault();
  $(".mask-container").show();
  var _this = $(this);
  $.ajax({
    url: _this.attr('action'),
    data: _this.serialize(),
    type: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(res){
      if (res.success) {
        var tbtn = $("#soal-{{ $soal->uuid }}");
        if (!tbtn.hasClass('btn-success')&&(_this.find("input[name='jawab']").is(":checked")&&_this.find("input[name='jawab']").val()!='')) {
          tbtn.removeClass('btn-default');
          tbtn.addClass('btn-success');
        }
        if ($(".btn-next").length) {
          $(".btn-next").click();
        }else {
          var btn = '#soal-{{ json_decode($allSoal)[0] }}';
          var soal = '{{ json_decode($allSoal)[0] }}';
          getSoal(soal,1,$(btn));
        }
      }
    }
  })
})

$("#btn-submit").click(function(){
  $("#form-ujian").submit();
})
lightbox.option({
  'resizeDuration': 200,
  'wrapAround': true
})
</script>
