<div class="modal-header">
  <h4 class="modal-title" id="">Tambah Jadwal Ujian</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form id="form-data" method="post" action="{{ route('jadwal.ujian.store') }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">Soal Ujian</label>
          <select class="form-control" id="get-soal" name="kode_soal">
            @foreach ($soal as $key => $v)
              <option value="{{ $v->kode }}">{{ '('.$v->kode.') '.$v->nama }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Kelas</label>
          <select class="form-control" name="kode_kelas">
            <option value="all">Semua Kelas</option>
            @if (count($kelas))
              @foreach ($kelas as $key => $v)
                <option value="{{ $v->kode }}">{{ '('.$v->kode.') '.$v->nama.' '.$v->jurusan.'/'.$v->tingkat }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Waktu Mulai Ujian</label>
          <input type="text" class="form-control" id="dstart" name="mulai_ujian" value="">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Waktu Selesai Ujian</label>
          <input type="text" class="form-control" id="dend" name="selesai_ujian" value="">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Lama Ujian (Menit)</label>
          <input min="1" type="number" class="form-control" name="lama_ujian" value="60">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Sesi Ujian</label>
          <input min="1" type="number" class="form-control" name="sesi_ujian" value="1">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Ruang Ujian</label>
          <input type="text" class="form-control" name="ruang_ujian" value="">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Acak Soal</label>
          <select class="form-control" name="acak_soal">
            <option value="Y">Ya</option>
            <option selected value="N">Tidak</option>
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Jumlah Digit PIN</label>
          <input type="number" class="form-control" name="pin_digit" value="4" placeholder="Default: 4">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Tampilkan Nilai</label>
          <select class="form-control" name="tampil_nilai">
            <option value="Y">Ya</option>
            <option selected value="N">Tidak</option>
          </select>
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
$("#form-data").submit(function(e){
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
</script>
