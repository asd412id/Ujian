<div class="modal-header">
  <h4 class="modal-title" id="">Ubah Soal</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form method="post" id="form-soal" action="{{ route('soal.item.update',['uuid'=>$item->uuid]) }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">Soal</label>
          <textarea name="soal" rows="8" id="soal" class="form-control" placeholder="Tulis soal disini...">{!! nl2br($item->soal) !!}</textarea>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 {{ $item->jenis_soal=='E'?'col-md-12':'' }}">
        <div class="form-group">
          <label class="bmd-label-floating">Jenis Soal</label>
          <select class="form-control" name="jenis_soal">
            <option {{ $item->jenis_soal=='P'?'selected':'' }} value="P">Pilihan Ganda</option>
            <option {{ $item->jenis_soal=='E'?'selected':'' }} value="E">Essay</option>
          </select>
        </div>
      </div>
      <div class="col-md-6" {{ $item->jenis_soal=='E'?'style=display:none':'' }} id="acak-wrap">
        <div class="form-group">
          <label class="bmd-label-floating">Acak Opsi</label>
          <select class="form-control" name="acak_soal">
            <option {{ $item->acak_opsi=='Y'?'selected':'' }} value="Y">Ya</option>
            <option {{ $item->acak_opsi=='N'||$item->acak_opsi==null?'selected':'' }} value="N">Tidak</option>
          </select>
        </div>
      </div>
    </div>
    <div class="row" {{ $item->jenis_soal=='E'?'style=display:none':'' }} id="opsi">
      <div class="col-md-12">
        <div class="h4" style="margin-top: 30px">Pilihan Jawaban
          <span class="pull-right">
            <button type="button" class="btn btn-sm btn-xs btn-link btn-add">
              <i class="material-icons">add</i> Tambah Opsi
            </button>
          </span>
        </div>
        <hr>
        <div id="opsi-wrapper">
          @if ($item->jenis_soal=='P')
            @if (count(json_decode($item->opsi)))
              @foreach (json_decode($item->opsi) as $key => $v)
                <div class="form-group">
                  <div>
                    <label class="bmd-label-floating">Opsi {{ $key+1 }}</label>
                    <input type="radio" {{ $key==$item->benar?'checked':'' }} name="benar" value="{{ $key }}" style="right: -10px;position: relative;bottom: -2px;" title="Jawaban Benar">
                    <button type="button" class="btn btn-xs btn-link btn-delete pull-right" title="Hapus Opsi"><i class="material-icons">close</i></button>
                  </div>
                  <input type="text" name="opsi[]" class="form-control opsi" value="{!! nl2br($v) !!}" autocomplete="off">
                </div>
              @endforeach
            @else
              <div class="form-group">
                <div>
                  <label class="bmd-label-floating">Opsi 1</label>
                  <input type="radio" name="benar" value="0" style="right: -10px;position: relative;bottom: -1px;" title="Jawaban Benar">
                  <button type="button" class="btn btn-xs btn-link btn-delete pull-right" title="Hapus Opsi"><i class="material-icons">close</i></button>
                </div>
                <input type="text" name="opsi[]" class="form-control" autocomplete="off">
              </div>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
<script>
(function(){
    function insertInto(str, input){
        var val = input.value, s = input.selectionStart, e = input.selectionEnd;
        input.value = val.slice(0,e)+str+val.slice(e);
        if (e==s) input.selectionStart += str.length - 1;
        input.selectionEnd = e + str.length -1;
    }
    var closures = {40:')',91:']', 123:'}'};
    $("textarea[name='soal']").keypress(function(e) {
        if (c = closures[e.which]) insertInto(c, this);
    });
    $("select[name='jenis_soal']").on('change',function(){
      if ($(this).val()=='E') {
        $(this).closest('.col-md-6').addClass('col-md-12');
        $("#acak-wrap,#opsi").hide();
      }else {
        $(this).closest('.col-md-6').removeClass('col-md-12');
        $("#acak-wrap,#opsi").show();
      }
    })

    var opsi = $("#opsi-wrapper").find('.form-group').first().get(0).outerHTML;

    function init() {
      $(".btn-add").click(function(e){
        e.stopPropagation();
        e.stopImmediatePropagation();
        $("#opsi-wrapper").append(opsi);
        $("#opsi-wrapper .form-group").last().find('input').val('');
        $("#opsi-wrapper .form-group").last().find('input').focus();
        init();
        refreshOpsi();
        initEditor();
      })
      $(".btn-delete").click(function(e){
        e.stopPropagation();
        e.stopImmediatePropagation();
        if ($("#opsi-wrapper .form-group").length==1) {
          md.showNotification('bottom','right','Opsi tidak boleh kosong','warning','not_interested');
          return false;
        }
        $(this).closest('.form-group').remove();
        init();
        refreshOpsi();
      })
    }

    function refreshOpsi() {
      $("#opsi-wrapper .form-group").each(function(i,v){
        $(this).find(".bmd-label-floating").text('Opsi '+(i+1));
        $(this).find("input[name='benar']").val(i);
      })
    }

    init();

    $("#form-soal").submit(function(e){
      var form = $(this);
      e.preventDefault();
      var data = $(this).serialize();
      $.ajax({
        url: $(this).attr('action'),
        data: data,
        type: 'post',
        headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(res){
          if (res.success) {
            form.unbind('submit');
            form.submit();
          }else {
            for (var i = 0; i < res.messages.length; i++) {
              md.showNotification('bottom','right',res.messages[i],'danger','not_interested');
            }
          }
        }
      })
    })
})();
</script>
