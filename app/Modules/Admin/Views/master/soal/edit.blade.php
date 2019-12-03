<div class="modal-header">
  <h4 class="modal-title" id="">Edit Bank Soal</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form method="post" action="{{ route('soal.update',['uuid'=>$data->uuid]) }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">Kode Soal</label>
          <input type="text" class="form-control" name="kode" value="{{ $data->kode }}" {{ $data->tes->count()?'readonly':'' }}>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Nama Bank Soal</label>
          <input type="text" class="form-control" name="nama" value="{{ $data->nama }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Mata Pelajaran</label>
          <select class="form-control" name="kode_mapel">
            @if (count($mapel))
              @foreach ($mapel as $key => $v)
                <option {{ $data->kode_mapel==$v->kode?'selected':'' }} value="{{ $v->kode }}">{{ $v->nama }}</option>
              @endforeach
            @endif
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
