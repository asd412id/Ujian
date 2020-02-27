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
              <input type="radio" {{ !is_null($jawaban)&&$jawaban==$key?'checked':'' }} name="jawab" class="jawab" value="{{ $key }}">
              {{ $range[$i++].'.' }}
            </td>
            <td style="vertical-align: top;padding: 5px 0">{!! nl2br($opsi) !!}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="2">
            <h4 style="font-weight: bold">Jawaban Anda:</h4>
            <textarea name="jawab" rows="5" class="form-control" placeholder="Ketik Jawaban Anda di Sini...">{{ $jawaban }}</textarea>
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
    <button type="button" class="btn btn-success" id="btn-submit">Simpan Jawaban & Lanjutkan</button>
    @if (array_key_exists($key+1,json_decode($allSoal)))
      <a class="btn btn-sm btn-default btn-soal-nav btn-next" data-btn="#soal-{{ json_decode($allSoal)[$key+1] }}" data-soal="{{ json_decode($allSoal)[$key+1] }}" data-key="{{ $key+2 }}" href="javascript:void(0)">
        Soal Berikutnya
      </a>
    @endif
  @endif
@endforeach
</div>
<script>
$(".otext").click(function(){
  $(this).find('.jawab').prop('checked',true);
})
$(".btn-soal-nav").click(function(){
  var _btn = $(this);
  var soal = _btn.data('soal');
  var key = _btn.data('key');
  getSoal(soal,key,$(_btn.data('btn')));
})

var submit = false;
function loginProcess(form){
  $.get('{{ route('token.generate') }}',function(token){
    if ($("meta[name='csrf-token']").length) {
      $("meta[name='csrf-token']").prop('content',token);
    }
    submit = true;
    form.submit();
  })
}

$("#form-ujian").submit(function(e){
  e.preventDefault();
  $(".mask-container").show();
  if (submit == true) {
    submit = false;
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
          if (!tbtn.hasClass('btn-success')&&((_this.find("input[name='jawab']").length>0&&_this.find("input[name='jawab']").is(":checked")&&_this.find("input[name='jawab']").val()!='')||(_this.find("textarea[name='jawab']").length>0&&_this.find("textarea[name='jawab']").val()!=''))) {
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
  }else {
    loginProcess($(this));
  }
})

$("#btn-submit").click(function(){
  $(this).prop('disabled',true);
  $("#form-ujian").submit();
})
lightbox.option({
  'resizeDuration': 200,
  'wrapAround': true
})
</script>
