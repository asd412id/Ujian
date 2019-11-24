@extends('Admin::layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <div class="pull-left">
          <h4 class="card-title ">Nilai {{ $jadwal->nama_ujian }} ({{ $jadwal->pin }})</h4>
          <p class="card-category">
            Jumlah Peserta: {{ count(json_decode($jadwal->peserta)) }}<br>
            Jenis Soal: {{ $jadwal->jenis_soal=='P'?'Pilihan Ganda':'Essay' }}
          </p>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class=" text-primary">
              <th>No</th>
              <th>No. Ujian</th>
              <th>NISN</th>
              <th>Nama</th>
              <th>Kelas</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($peserta))
                @foreach ($peserta as $key => $v)
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $v->noujian }}</td>
                    <td>{{ $v->nik }}</td>
                    <td>{{ $v->nama }}</td>
                    <td>{{ $v->kelas->nama }}</td>
                    <td style="white-space: nowrap;width: 50px" class="text-right">
                      @if ($v->attemptLogin()->where('pin',$jadwal->pin)->first())
                        <a class="btn btn-sm btn-xs btn-success" title="Download Nilai Ujian" href="{{ route('nilai.detail.download',['jadwal'=>$jadwal->uuid,'siswa'=>$v->uuid]) }}" class="text-info"><i class="fa fa-fw fa-download"></i></a>
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
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
