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
              <th>PIN</th>
              <th>Soal</th>
              <th>Kelas</th>
              <th>Lama Ujian</th>
              <th>Sesi</th>
              <th>Jumlah Peserta</th>
              <th></th>
            </thead>
            <tbody>
              @if (count($jadwal))
                @foreach ($jadwal as $key => $v)
                  @php
                    $sdata = [];
                    $soal = $v->getSoal->item()->select('uuid')->get()->pluck('uuid');
                    $peserta = $tes->where('pin',$v->pin)->whereIn('soal_item',$soal)->with('siswa')->get();
                    foreach ($peserta as $key1 => $p) {
                      if (!in_array($p->noujian,$sdata)) {
                        array_push($sdata,$p->noujian);
                      }
                    }
                  @endphp
                  <tr>
                    @php
                    $index = Request::get('page')??1;
                    @endphp
                    <td>{{ (($index-1)*10)+$key+1 }}</td>
                    <td>{{ $v->pin }}</td>
                    <td>{{ $v->getSoal?'('.$v->getSoal->kode.') '.$v->getSoal->nama:'-' }}</td>
                    <td>{{ $v->kelas?'('.$v->kelas->kode.') '.$v->kelas->nama.' '.$v->kelas->jurusan:($v->kode_kelas=='all'?'Semua Kelas':'-') }}</td>
                    <td>{{ $v->lama_ujian.' Menit' }}</td>
                    <td>{{ $v->sesi_ujian }}</td>
                    <td>{{ $v->kelas?$v->kelas->siswa->count():App\Models\Siswa::count() }}</td>
                    <td style="white-space: nowrap;width: 50px" class="text-right">
                      <a class="btn btn-sm btn-xs btn-success" target="_blank" title="Download Nilai Ujian" href="{{ route('nilai.download',['uuid'=>$v->uuid]) }}" class="text-info"><i class="fa fa-fw fa-download"></i></a>
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
