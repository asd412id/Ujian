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
