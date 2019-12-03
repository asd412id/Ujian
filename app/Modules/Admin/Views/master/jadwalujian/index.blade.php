@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Jadwal Ujian</h4>
          <p class="card-category">Menambah, Mengubah, dan Menghapus Jadwal Ujian</p>
        </div>
        <div class="pull-right">
          <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('jadwal.ujian.create') }}"><i class="material-icons">add</i> Tambah Jadwal</a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class=" text-primary">
              <th>No</th>
              <th>Nama</th>
              <th>Peserta</th>
              <th>Soal</th>
              <th>Jenis</th>
              <th>Bobot</th>
              <th>Mulai</th>
              <th>Selesai</th>
              <th>Lama Ujian</th>
              <th>PIN</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($jadwal))
                @foreach ($jadwal as $key => $v)
                  @php
                  $sdata = [];
                  $soal = json_decode($v->soal);
                  $kelas = '';
                  $mapel = '';

                  $getKelas = App\Models\Kelas::whereHas('siswa',function($q) use($v){
                    $q->whereIn('uuid',json_decode($v->peserta));
                  })
                  ->orderBy('tingkat','asc')
                  ->select('nama')
                  ->get();

                  if (count($getKelas)) {
                    foreach ($getKelas as $key1 => $k) {
                      $kelas .= $k->nama;
                      if ($key1 < count($getKelas)-2) {
                        $kelas .= ', ';
                      }elseif ($key1 == count($getKelas)-2) {
                        if (count($getKelas) > 2) {
                          $kelas .= ',';
                        }
                        $kelas .= ' dan ';
                      }
                    }
                  }

                  $getMapel = App\Models\Mapel::whereHas('soal',function($q) use($v){
                    $q->whereIn('uuid',json_decode($v->soal));
                  })
                  ->orderBy('id','asc')
                  ->select('nama')
                  ->get();

                  if (count($getMapel)) {
                    foreach ($getMapel as $key2 => $m) {
                      $mapel .= $m->nama;
                      if ($key2 < count($getMapel)-2) {
                        $mapel .= ', ';
                      }elseif ($key2 == count($getMapel)-2) {
                        if (count($getMapel) > 2) {
                          $mapel .= ',';
                        }
                        $mapel .= ' dan ';
                      }
                    }
                  }
                  @endphp
                  <tr>
                    @php
                    $index = Request::get('page')??1;
                    @endphp
                    <td style="vertical-align: top">{{ (($index-1)*30)+$key+1 }}</td>
                    <td style="vertical-align: top">
                      {{ $v->nama_ujian }}<br>
                      <span class="badge badge-primary">{{ $mapel }}</span><br>
                      <span class="badge badge-danger">Kelas {{ $kelas }}</span>
                    </td>
                    <td style="vertical-align: top">{{ @count(json_decode($v->peserta)) }}</td>
                    <td style="vertical-align: top">{{ $v->jumlah_soal }}</td>
                    <td style="vertical-align: top">{{ $v->jenis_soal=='P'?'Pilihan Ganda':'Essay' }}</td>
                    <td style="vertical-align: top">{{ $v->bobot }}</td>
                    <td style="vertical-align: top">{{ date('d/m/Y H:i',strtotime($v->mulai_ujian)) }}</td>
                    <td style="vertical-align: top">{{ date('d/m/Y H:i',strtotime($v->selesai_ujian)) }}</td>
                    <td style="vertical-align: top">{{ $v->lama_ujian.' Menit' }}</td>
                    <td style="font-weight:bold;vertical-align: top" class="text-primary">{{ $v->pin }}</td>
                    <td style="width: 125px;vertical-align: top" class="text-center">
                      @if (($v->aktif||is_null($v->aktif)))
                      <a class="btn btn-sm btn-xs {{ $v->aktif?'btn-yellow':'btn-primary' }} confirm" title="{{ $v->aktif?'Nonaktifkan':'Aktifkan' }} Jadwal Ujian" data-text="{{ $v->aktif?'Nonaktifkan Jadwal Ujian?<br>Semua peserta akan logout!':'Hasil ujian sebelumnya akan terhapus!<br>Aktifkan Jadwal Ujian?' }}" href="#" data-url="{{ route('jadwal.ujian.activate',['uuid'=>$v->uuid]) }}">{!! $v->aktif?'<i class="material-icons">close</i>':'<i class="material-icons">check</i>' !!}</a>
                      @if ($v->aktif)
                        <a class="btn btn-sm btn-xs btn-info" title="Lihat status peserta" href="{{ route('jadwal.ujian.monitoring.detail',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">desktop_windows</i></a>
                      @endif
                      @endif
                      @if ($v->peserta && count(json_decode($v->peserta)))
                        <a href="{{ route('jadwal.ujian.print.kartu',['uuid'=>$v->uuid]) }}" target="_blank" class="btn btn-sm btn-xs btn-success" title="Cetak Kartu Ujian"><i class="material-icons">subtitles</i></a>
                        <a href="{{ route('jadwal.ujian.print.absen',['uuid'=>$v->uuid]) }}" target="_blank" class="btn btn-sm btn-xs btn-warning" title="Cetak Daftar Hadir"><i class="material-icons">assignment</i></a>
                        <a href="{{ route('jadwal.ujian.print.berita',['uuid'=>$v->uuid]) }}" target="_blank" class="btn btn-sm btn-xs btn-blue" title="Cetak Berita Acara"><i class="material-icons">book</i></a>
                      @endif
                      @if (!$v->aktif)
                        <a class="btn btn-sm btn-xs btn-info" title="Ubah" href="#" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalEdit" data-url="{{ route('jadwal.ujian.edit',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">edit</i></a>
                      @if (is_null($v->aktif))
                      @endif
                      <a class="btn btn-sm btn-xs btn-danger confirm" title="Hapus" data-text="Semua jawaban & nilai peserta akan terhapus untuk jadwal ini!<br><b>Hapus Jadwal Ujian {{ $v->nama_ujian }}</b>" href="#" data-url="{{ route('jadwal.ujian.destroy',['uuid'=>$v->uuid]) }}"><i class="material-icons">delete</i></a>
                       @endif
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
          {{ $jadwal->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
  <div class="modal fade" id="modalEdit" role="dialog" aria-labelledby="" aria-hidden="true">
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
        _this.find('.modal-dialog').animate({'max-width':'850px'},150,function(){
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
