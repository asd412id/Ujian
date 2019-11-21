@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Reset Data</h4>
          <p class="card-category"><span class="bg-danger" style="font-weight:bold">Peringatan: Data yang telah direset tidak dapat dikembalikan!</span></p>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody style="font-weight: bold">
            <tr>
              <td>Data Sekolah</td>
              <td class="text-right">
                <a class="btn btn-sm btn-xs btn-danger confirm" title="Reset" data-text="Reset Data Sekolah?" href="#" data-url="{{ route('admin.reset.data',['data'=>'sekolah']) }}"><i class="material-icons">delete</i></a>
              </td>
            </tr>
            <tr>
              <td>Data Kelas</td>
              <td class="text-right">
                <a class="btn btn-sm btn-xs btn-danger confirm" title="Reset" data-text="Reset Data Kelas?" href="#" data-url="{{ route('admin.reset.data',['data'=>'kelas']) }}"><i class="material-icons">delete</i></a>
              </td>
            </tr>
            <tr>
              <td>Data Mata Pelajaran</td>
              <td class="text-right">
                <a class="btn btn-sm btn-xs btn-danger confirm" title="Reset" data-text="Reset Data Mata Pelajaran?" href="#" data-url="{{ route('admin.reset.data',['data'=>'mapel']) }}"><i class="material-icons">delete</i></a>
              </td>
            </tr>
            <tr>
              <td>Data Siswa</td>
              <td class="text-right">
                <a class="btn btn-sm btn-xs btn-danger confirm" title="Reset" data-text="Reset Data Siswa?" href="#" data-url="{{ route('admin.reset.data',['data'=>'siswa']) }}"><i class="material-icons">delete</i></a>
              </td>
            </tr>
            <tr>
              <td>Data Soal (Termasuk Jadwal & Nilai)</td>
              <td class="text-right">
                <a class="btn btn-sm btn-xs btn-danger confirm" title="Reset" data-text="Reset Data Soal?" href="#" data-url="{{ route('admin.reset.data',['data'=>'soal']) }}"><i class="material-icons">delete</i></a>
              </td>
            </tr>
            <tr>
              <td>Data Jadwal Ujian (Termasuk Nilai)</td>
              <td class="text-right">
                <a class="btn btn-sm btn-xs btn-danger confirm" title="Reset" data-text="Reset Data Jadwal Ujian?" href="#" data-url="{{ route('admin.reset.data',['data'=>'jadwal']) }}"><i class="material-icons">delete</i></a>
              </td>
            </tr>
            <tr>
              <td>Data Media</td>
              <td class="text-right">
                <a class="btn btn-sm btn-xs btn-danger confirm" title="Reset" data-text="Reset Data Media?" href="#" data-url="{{ route('admin.reset.data',['data'=>'media']) }}"><i class="material-icons">delete</i></a>
              </td>
            </tr>
          </tbody>
        </table>
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
