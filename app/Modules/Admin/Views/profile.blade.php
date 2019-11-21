@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-lg-6 offset-lg-3">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Administrator</h4>
          <p class="card-category">Anda harus login ulang jika telah mengubah data!</p>
        </div>
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('admin.profile.update') }}">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Nama</label>
                <input type="text" class="form-control" name="nama" value="{{ old('nama')?old('nama'):$data->nama }}">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Username</label>
                <input type="text" class="form-control" name="username" value="{{ old('username')?old('username'):$data->username }}">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Password</label>
                <input type="password" class="form-control" name="password" value="">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="bmd-label-floating">Konfirmasi Password</label>
                <input type="password" class="form-control" name="password_confirmation" value="">
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary pull-right">Ubah Profil</button>
          <div class="clearfix"></div>
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
