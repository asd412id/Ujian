@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Monitoring Ujian</h4>
          <p class="card-category">Status Ujian Siswa</p>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class=" text-primary">
              <th>No</th>
              <th>Soal</th>
              <th>Kelas</th>
              <th>Mulai</th>
              <th>Lama Ujian</th>
              <th>Sesi</th>
              <th>PIN</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($jadwal))
                @foreach ($jadwal as $key => $v)
                  <tr>
                    @php
$index = Request::get('page')??1;
@endphp
<td>{{ (($index-1)*10)+$key+1 }}</td>
                    <td>{{ $v->getSoal->nama??'-' }}</td>
                    <td>{{ $v->getSoal->kelas->nama??'-' }}</td>
                    <td>{{ date('d/m/Y H:i',strtotime($v->updated_at)) }}</td>
                    <td>{{ $v->lama_ujian.' Menit' }}</td>
                    <td>{{ $v->sesi_ujian }}</td>
                    <td style="font-weight:bold" class="text-primary">{{ $v->pin }}</td>
                    <td style="white-space: nowrap;width: 50px" class="text-right">
                      <a class="btn btn-sm btn-xs btn-info" title="Lihat status peserta" href="{{ route('jadwal.ujian.monitoring.detail',['uuid'=>$v->uuid]) }}" class="text-info"><i class="material-icons">desktop_windows</i></a>
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
