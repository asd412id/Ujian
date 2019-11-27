@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Nilai Hasil Ujian</h4>
          <p class="card-category">Nilai Akhir Hasil Ujian Peserta</p>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class=" text-primary">
              <th>No</th>
              <th>Nama Ujian</th>
              <th>PIN</th>
              <th>Soal</th>
              <th>Jenis</th>
              <th>Lama Ujian</th>
              <th>Jumlah Peserta</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($jadwal))
                @foreach ($jadwal as $key => $v)
                  @php
                    $sdata = [];
                    $soal = json_decode($v->soal);
                  @endphp
                  <tr>
                    @php
                    $index = Request::get('page')??1;
                    @endphp
                    <td>{{ (($index-1)*30)+$key+1 }}</td>
                    <td>{{ $v->nama_ujian }}</td>
                    <td>{{ $v->pin }}</td>
                    <td>{{ $v->jumlah_soal }}</td>
                    <td>{{ $v->jenis_soal=='P'?'Pilihan Ganda':'Essay' }}</td>
                    <td>{{ $v->lama_ujian.' Menit' }}</td>
                    <td>{{ count(json_decode($v->peserta)) }}</td>
                    <td style="white-space: nowrap;width: 50px" class="text-right">
                      <a class="btn btn-sm btn-xs btn-primary" title="Detail Nilai Ujian" href="{{ route('nilai.detail',['uuid'=>$v->uuid]) }}" class="text-info"><i class="fa fa-fw fa-address-book"></i></a>
                      @if ($v->jenis_soal=='P')
                        <a class="btn btn-sm btn-xs btn-success" title="Download Nilai Excel" href="{{ route('nilai.download.excel',['uuid'=>$v->uuid]) }}" class="text-info"><i class="fa fa-fw fa-file-excel"></i></a>
                        <a class="btn btn-sm btn-xs btn-danger" title="Download Nilai PDF" href="{{ route('nilai.download.pdf',['uuid'=>$v->uuid]) }}" class="text-info"><i class="fa fa-fw fa-file-pdf"></i></a>
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
