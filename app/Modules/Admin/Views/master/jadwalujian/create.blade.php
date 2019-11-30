<div class="modal-header">
  <h4 class="modal-title" id="">Tambah Jadwal Ujian</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form id="form-data" method="post" action="{{ route('jadwal.ujian.store') }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="bmd-label-floating">Nama Ujian</label>
          <textarea name="nama_ujian" rows="4" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Jumlah Soal</label>
          <input type="number" class="form-control" id="jumlah_soal" name="jumlah_soal" value="">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Bobot</label>
          <input type="number" class="form-control" id="bobot" name="bobot" value="100">
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
      </div>
      <div class="col-md-8">
        <div class="form-group">
          <label class="bmd-label-floating">Jenis Soal</label>
          <select class="form-control select2" id="jenis_soal" name="jenis_soal">
            <option value="P">Pilihan Ganda</option>
            <option value="E">Essay</option>
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Soal Ujian</label>
          <select class="form-control select2-multiple" id="get_soal" name="soal[]" data-url="{{ route('jadwal.ujian.ajax.getsoal') }}" multiple data-placeholder="Cari bank soal"></select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Peserta</label>
          <select class="form-control select2-multiple" id="get_peserta" name="peserta[]" multiple data-url="{{ route('jadwal.ujian.ajax.getpeserta') }}" data-placeholder="Cari peserta"></select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Acak Soal</label>
          <select class="form-control select2" name="acak_soal">
            <option selected value="Y">Ya</option>
            <option value="N">Tidak</option>
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Jumlah Digit PIN</label>
          <input type="number" class="form-control" name="pin_digit" value="4" placeholder="Default: 4">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Tampilkan Nilai</label>
          <select class="form-control select2" name="tampil_nilai">
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
initModalScripts();
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
