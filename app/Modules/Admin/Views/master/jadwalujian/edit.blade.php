<div class="modal-header">
  <h4 class="modal-title" id="">Tambah Jadwal Ujian</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form id="form-data" method="post" action="{{ route('jadwal.ujian.update',['uuid'=>$data->uuid]) }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="bmd-label-floating">Nama Ujian</label>
          <textarea name="nama_ujian" rows="4" class="form-control">{{ $data->nama_ujian }}</textarea>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Jumlah Soal</label>
          <input type="number" class="form-control" id="jumlah_soal" name="jumlah_soal" value="{{ $data->jumlah_soal }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Bobot Soal</label>
          <input type="number" class="form-control" id="bobot" name="bobot" value="{{ $data->bobot }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Waktu Mulai Ujian</label>
          <input type="text" class="form-control" id="dstart" name="mulai_ujian" value="{{ date('d/m/Y - H:i',strtotime($data->mulai_ujian)) }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Waktu Selesai Ujian</label>
          <input type="text" class="form-control" id="dend" name="selesai_ujian" value="{{ date('d/m/Y - H:i',strtotime($data->selesai_ujian)) }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Lama Ujian (Menit)</label>
          <input min="1" type="number" class="form-control" name="lama_ujian" value="{{ $data->lama_ujian }}">
        </div>
      </div>
      <div class="col-md-8">
        <div class="form-group">
          <label class="bmd-label-floating">Jenis Soal</label>
          <select class="form-control select2" id="jenis_soal" name="jenis_soal">
            <option {{ $data->jenis_soal=='P'?'selected':'' }} value="P">Pilihan Ganda</option>
            <option {{ $data->jenis_soal=='E'?'selected':'' }} value="E">Essay</option>
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Soal Ujian</label>
          <select class="form-control select2-multiple" id="get_soal" multiple name="soal[]" data-url="{{ route('jadwal.ujian.ajax.getsoal') }}" data-placeholder="Cari bank soal">
            @foreach ($soal as $key => $v)
              <option selected value="{{ $v->uuid }}">{{ '('.$v->kode.') '.$v->nama }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Peserta</label>
          <select class="form-control select2-multiple" id="get_peserta" name="peserta[]" data-url="{{ route('jadwal.ujian.ajax.getpeserta') }}" multiple data-placeholder="Cari peserta">
            @if (count($siswa))
              @foreach ($siswa as $key => $v)
                <option selected value="{{ $v->uuid }}">{{ '('.$v->kelas->nama.') ('.$v->noujian.') '.$v->nama }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Acak Soal</label>
          <select class="form-control select2" name="acak_soal">
            <option {{ $data->acak_soal=='Y'?'selected':'' }} value="Y">Ya</option>
            <option {{ $data->acak_soal=='N'?'selected':'' }} value="N">Tidak</option>
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Tampilkan Nilai</label>
          <select class="form-control select2" name="tampil_nilai">
            <option {{ $data->tampil_nilai=='Y'?'selected':'' }} value="Y">Ya</option>
            <option {{ $data->tampil_nilai=='N'?'selected':'' }} value="N">Tidak</option>
          </select>
          <div class="form-group text-center" style="margin-top: 30px">
            <h5>PIN:</h5>
            <span class="text-success" style="font-weight: bold;font-size: 2em">
              {{ $data->pin }}
            </span>
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
