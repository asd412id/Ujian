<div class="modal-header">
  <h4 class="modal-title" id="">Ubah {{ $data->nama.' '.$data->jurusan }}</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form method="post" action="{{ route('master.mapel.update',['uuid'=>$data->uuid]) }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">Kode Mata Pelajaran</label>
          <input type="text" class="form-control" name="kode" value="{{ $data->kode }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Nama Mata Pelajaran</label>
          <input type="text" class="form-control" name="nama" value="{{ $data->nama }}">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
