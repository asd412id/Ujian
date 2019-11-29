@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Data Kelas</h4>
          <p class="card-category">Menambah, Mengubah, dan Menghapus Data Kelas</p>
        </div>
        <div class="pull-right">
          <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('master.kelas.create') }}"><i class="material-icons">add</i> Tambah Kelas</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class=" text-primary">
              <th>No</th>
              <th>Kode Kelas</th>
              <th>Nama Kelas</th>
              <th>Jurusan</th>
              <th>Tingkat</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($kelas))
                @foreach ($kelas as $key => $v)
                  <tr>
                    @php
                    $index = Request::get('page')??1;
                    @endphp
                    <td>{{ (($index-1)*30)+$key+1 }}</td>
                    <td>{{ $v->kode??'-' }}</td>
                    <td>{{ $v->nama??'-' }}</td>
                    <td>{{ $v->jurusan??'-' }}</td>
                    <td>{{ $v->tingkat??'-' }}</td>
                    <td style="white-space: nowrap;width: 50px" class="text-right">
                      @if (count($v->siswa))
                        <a href="{{ route('jadwal.ujian.print.kartu',['uuid'=>$v->uuid]) }}" target="_blank" class="btn btn-sm btn-xs btn-success" title="Cetak Kartu Ujian"><i class="material-icons">subtitles</i></a>
                        <a class="btn btn-sm btn-xs btn-primary" title="Lihat Siswa" href="{{ route('master.siswa.index',['cari'=>$v->kode]) }}" class="text-info"><i class="material-icons">group</i></a>
                      @endif
                      <a class="btn btn-sm btn-xs btn-info" title="Ubah" href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('master.kelas.edit',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">edit</i></a>
                      <a class="btn btn-sm btn-xs btn-danger delete" title="Hapus" data-text="Kelas {{ $v->nama.' '.$v->jurusan }}" href="#" data-url="{{ route('master.kelas.destroy',['uuid'=>$v->uuid]) }}"><i class="material-icons">delete</i></a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td class="text-center no-data">Data tidak tersedia</td>
                </tr>
              @endif
            </tbody>
          </table>
          {{ $kelas->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
  <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content"></div>
    </div>
  </div>
  <script type="text/javascript">
  $(document).ready(function(){
    $('#modalEdit').on('show.bs.modal', function (e) {
      var _this = $(this);
      var _data = $(e.relatedTarget);
      _this.find('.modal-dialog').css('max-width','200px')
      _this.find('.modal-content').html('<h4 style="margin: 0">Silahkan tunggu...</h4>');
      $.get(_data.data('url'),{},function(res){
        _this.find('.modal-dialog').animate({'max-width':'400px'},150,function(){
          _this.find('.modal-content').html(res)
        })
      });
    });
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
