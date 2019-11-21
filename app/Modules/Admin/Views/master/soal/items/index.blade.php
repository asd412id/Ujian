@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Detail Soal {{ $soal->nama }} ({{ $soal->kode }})</h4>
          <p class="card-category">Menambah, Mengubah, dan Menghapus Data Soal</p>
        </div>
        <div class="pull-right">
          <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('soal.item.create',['uuid'=>$soal->uuid]) }}"><i class="material-icons">add</i> Tambah Soal</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="text-primary">
              <th>No</th>
              <th style="white-space: nowrap">Jenis Soal</th>
              <th>Soal</th>
              <th>Jumlah Opsi</th>
              <th>Acak</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($items))
                @foreach ($items as $key => $v)
                  <tr>
                    @php
                    $index = Request::get('page')??1;
                    @endphp
                    <td style="vertical-align: top">{{ (($index-1)*10)+$key+1 }}</td>
                    <td style="white-space: nowrap;vertical-align: top">{{ $v->jenis_soal=='P'?'Pilihan Ganda':'Essay' }}</td>
                    <td style="vertical-align: top">{{ $v->soal?$helper->limitText(strip_tags($v->soal),25):'-' }}</td>
                    <td style="vertical-align: top">{{ $v->jenis_soal=='P'&&!is_null($v->opsi)&&$v->opsi!='null'?count(json_decode($v->opsi)):'-' }}</td>
                    <td style="vertical-align: top">
                      @if ($v->jenis_soal=='P')
                        {{ $v->acak_opsi=='Y'?'Ya':'Tidak' }}
                      @else
                        -
                      @endif
                    </td>
                    <td style="white-space: nowrap;width: 50px;vertical-align: top" class="text-right">
                      <a class="btn btn-sm btn-xs btn-success" title="Lihat Soal" href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('soal.item.show',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">search</i></a>
                      <a class="btn btn-sm btn-xs btn-info" title="Ubah" href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('soal.item.edit',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">edit</i></a>
                      <a class="btn btn-sm btn-xs btn-danger delete" title="Hapus" data-text="soal nomor {{ $key+1 }}" href="#" data-url="{{ route('soal.item.destroy',['uuid'=>$v->uuid]) }}"><i class="material-icons">delete</i></a>
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
          {{ $items->links() }}
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
  <script src="{{ url('assets/plugins/tinymce/tinymce.min.js') }}" charset="utf-8"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $('#modalEdit').on('show.bs.modal', function (e) {
      var _this = $(this);
      var _data = $(e.relatedTarget);
      _this.find('.modal-dialog').css('max-width','200px')
      _this.find('.modal-content').html('<h4 style="margin: 0">Silahkan tunggu...</h4>');
      $.get(_data.data('url'),{},function(res){
        _this.find('.modal-dialog').animate({'max-width':'600px'},150,function(){
          _this.find('.modal-content').html(res)
          tinymce.remove();
          tinymce.init({
            selector: '#soal',
            auto_focus: 'soal',
            menubar: false,
            statusbar: false,
            toolbar1: "undo redo bold italic underline strikethrough removeformat",
            setup: function (editor) {
              editor.on('change', function () {
                tinymce.triggerSave();
              });
            }
          });
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
