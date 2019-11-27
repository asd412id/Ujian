@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Bank Soal</h4>
          <p class="card-category">Menambah, Mengubah, dan Menghapus Data Soal</p>
        </div>
        <div class="pull-right">
          <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('soal.create') }}"><i class="material-icons">add</i> Tambah Bank Soal</a>
          @if (!App\Models\JadwalUjian::where('aktif',1)->count())
            <button type="button" class="btn btn-sm btn-xs btn-upload btn-success" data-target="#upload-excel" title="Impor dari file excel">
              <i class="fa fa-fw fa-file-excel"></i> Impor Excel
            </button>
            <form class="d-none" action="{{ route('import.soal') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="file" name="excel" id="upload-excel" class="d-none" accept=".xlsx,.xls">
            </form>
            <a href="{{ route('download.template',['type'=>'soal']) }}" class="btn btn-sm btn-info"><i class="material-icons">cloud_download</i> Download Excel Template</a>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class=" text-primary">
              <th>No</th>
              <th>Kode Soal</th>
              <th>Nama Bank Soal</th>
              <th>Mata Pelajaran</th>
              <th>Jumlah Soal</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($soal))
                @foreach ($soal as $key => $v)
                  <tr>
                    @php
                    $index = Request::get('page')??1;
                    @endphp
                    <td>{{ (($index-1)*30)+$key+1 }}</td>
                    <td>{{ $v->kode??'-' }}</td>
                    <td>{{ $v->nama??'-' }}</td>
                    <td>{{ $v->mapel?$v->mapel->nama:'-' }}</td>
                    <td>{{ @$v->item->count()??'-' }}</td>
                    <td style="white-space: nowrap;width: 50px" class="text-right">
                      <a class="btn btn-sm btn-xs btn-success" title="Lihat Bank Soal" href="{{ route('soal.detail',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">file_copy</i></a>
                      <a class="btn btn-sm btn-xs btn-info" title="Ubah" href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('soal.edit',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">edit</i></a>
                      <a class="btn btn-sm btn-xs btn-danger delete" title="Hapus" data-text="{{ $v->nama }}" href="#" data-url="{{ route('soal.destroy',['uuid'=>$v->uuid]) }}"><i class="material-icons">delete</i></a>
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
          {{ $soal->links() }}
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
    $(".btn-upload").click(function(){
      var tr = $(this).data('target');
      $(tr).click();
    })
    $("#upload-excel").change(function(){
      $(this).parent().submit();
    })
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
