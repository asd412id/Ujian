@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title">Profil Sekolah</h4>
          <p class="card-category">Ubah Data Profil Sekolah</p>
        </div>
        <span class="pull-right">
          <button type="button" class="btn btn-xs btn-upload btn-success" data-target="#upload-excel" title="Impor dari file excel">
            <i class="fa fa-fw fa-file-excel"></i> Impor
          </button>
          <form class="d-none" action="{{ route('sekolah.import') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="excel" id="upload-excel" class="d-none" accept=".xlsx,.xls">
          </form>
          <a href="{{ route('download.template',['type'=>'master']) }}" class="btn btn-xs btn-info"><i class="material-icons">cloud_download</i> Download Excel Template</a>
        </span>
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('sekolah.store') }}">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="bmd-label-floating">Kode Sekolah</label>
                <input type="text" class="form-control" name="kode" value="{{ old('kode')?old('kode'):@$sekolah->kode }}">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label class="bmd-label-floating">Nama Sekolah</label>
                <input type="text" class="form-control" name="nama" value="{{ old('nama')?old('nama'):@$sekolah->nama }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Alamat</label>
                <input type="text" class="form-control" name="alamat" value="{{ old('alamat')?old('alamat'):@$sekolah->alamat }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="bmd-label-floating">Kota</label>
                <input type="text" class="form-control" name="kota" value="{{ old('kota')?old('kota'):@$sekolah->kota }}">
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label class="bmd-label-floating">Propinsi</label>
                <input type="text" class="form-control" name="propinsi" value="{{ old('propinsi')?old('propinsi'):@$sekolah->propinsi }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="bmd-label-floating">Kode Pos</label>
                <input type="text" class="form-control" name="kodepos" value="{{ old('kodepos')?old('kodepos'):@$sekolah->kodepos }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">No. Telepon</label>
                <input type="text" class="form-control" name="telp" value="{{ old('telp')?old('telp'):@$sekolah->telp }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="bmd-label-floating">Fax</label>
                <input type="text" class="form-control" name="fax" value="{{ old('fax')?old('fax'):@$sekolah->fax }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Kop Dokumen</label>
                <textarea name="kop_kartu" rows="4" class="form-control" placeholder="PEMERINTAH KABUPATEN SINJAI&#10;DINAS PENDIDIKAN" autofocus>{{ old('kop_kartu')?old('kop_kartu'):@$sekolah->kop_kartu }}</textarea>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary pull-right">Simpan Profil</button>
          <div class="clearfix"></div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-profile">
      <div class="card-body text-left">
        <h6 class="card-category text-gray">Informasi Server</h6>
        @if (count($ipv4))
          @foreach ($ipv4 as $key => $ip)
            <p>{{ $key }}</p>
            <p class="font-weight-bold" style="font-size: 1.3em">{{ $ip }}</p>
          @endforeach
        @else
          {{ 'Alamat IP tidak ditemukan' }}
        @endif
      </div>
    </div>
    <div class="card card-profile" style="margin-top: 75px">
      <div class="card-avatar">
        <a href="#pablo">
          <img class="img" src="{{ @$sekolah->dept_logo&&is_file(base_path('uploads/'.$sekolah->dept_logo))?url('uploads',$sekolah->dept_logo):url('assets/img/noimage.png') }}" />
        </a>
      </div>
      <div class="card-body">
        <h6 class="card-category text-gray">Logo Departemen</h6>
        <button type="button" class="btn btn-primary btn-round" onclick="$(this).parent().find('input[name=\'logo\']').click()">Ubah</button>
        <form class="d-none" action="{{ route('sekolah.upload.logo',['type'=>'departemen']) }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="file" name="logo" onchange="$(this).parent().submit();$(this).closest('.card-body').find('button').prop('disabled',true);$(this).closest('.card-body').find('button').text('Sedang Mengupload...')" accept="image/jpeg,image/gif,image/x-png">
        </form>
      </div>
    </div>
    <div class="card card-profile" style="margin-top: 75px">
      <div class="card-avatar">
        <a href="#pablo">
          <img class="img" src="{{ @$sekolah->logo&&is_file(base_path('uploads/'.$sekolah->logo))?url('uploads',$sekolah->logo):url('assets/img/noimage.png') }}" />
        </a>
      </div>
      <div class="card-body">
        <h6 class="card-category text-gray">Logo Sekolah</h6>
        <button type="button" class="btn btn-primary btn-round" onclick="$(this).parent().find('input[name=\'logo\']').click()">Ubah</button>
        <form class="d-none" action="{{ route('sekolah.upload.logo',['type'=>'sekolah']) }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="file" name="logo" onchange="$(this).parent().submit();$(this).closest('.card-body').find('button').prop('disabled',true);$(this).closest('.card-body').find('button').text('Sedang Mengupload...')" accept="image/jpeg,image/gif,image/x-png">
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
$(".btn-upload").click(function(){
  var tr = $(this).data('target');
  $(tr).click();
})
$("#upload-excel").change(function(){
  $(this).parent().submit();
})
@if (session()->has('message'))
  md.showNotification('bottom','right','{{ session()->get('message') }}','success','check');
@endif
@if ($errors->any())
  @foreach ($errors->all() as $error)
    md.showNotification('bottom','right','{{ $error }}','danger','not_interested');
  @endforeach
@endif
</script>
@endsection
