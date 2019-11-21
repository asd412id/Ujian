<div class="modal-header">
  <h4 class="modal-title" id="">Tambah Siswa</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<form method="post" action="{{ route('master.siswa.store') }}">
  {{ csrf_field() }}
  <div class="modal-body text-left">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label class="bmd-label-floating">Nomor Ujian</label>
          <input type="text" class="form-control" name="noujian" value="{{ old('noujian') }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">NISN</label>
          <input type="text" class="form-control" name="nik" value="{{ old('nik') }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Nama</label>
          <input type="text" class="form-control" name="nama" value="{{ old('nama') }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Kelas</label>
          <select class="form-control" name="kode_kelas">
            @if (count($kelas))
              @foreach ($kelas as $key => $v)
                <option value="{{ $v->kode }}">{{ $v->nama.' '.$v->jurusan.' ('.$v->tingkat.')' }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Password</label>
          <input type="text" class="form-control" name="password" value="{{ old('password') }}">
        </div>
        <div class="form-group">
          <label class="bmd-label-floating">Foto</label>
          <input type="text" class="form-control" name="photo" value="{{ old('photo') }}">
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
