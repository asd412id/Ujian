<div class="modal-header">
  <h4 class="modal-title" id="">Edit Siswa</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form method="post" action="{{ route('master.siswa.update',['uuid'=>$data->uuid]) }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">Nomor Ujian</label>
          <input type="text" class="form-control" name="noujian" value="{{ $data->noujian }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">NISN</label>
          <input type="text" class="form-control" name="nik" value="{{ $data->nik }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Nama</label>
          <input type="text" class="form-control" name="nama" value="{{ $data->nama }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Kelas</label>
          <select class="form-control" name="kode_kelas">
            @if (count($kelas))
              @foreach ($kelas as $key => $v)
                <option {{ $data->kode_kelas==$v->kode?'selected':'' }} value="{{ $v->kode }}">{{ $v->nama.' '.$v->jurusan.' ('.$v->tingkat.')' }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Password</label>
          <input type="text" class="form-control" name="password" placeholder="Isi untuk mengganti password login siswa" value="{{ $data->real_password }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Foto</label>
          <input type="text" class="form-control" name="photo" value="{{ $data->photo }}">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
