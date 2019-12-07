@extends('Ujian::layout')
@section('content')
<div class="row">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Data Pribadi</h4>
        <p class="card-category">Data pribadi peserta ujian.</p>
      </div>
      <div class="card-body">
        <table class="table" style="font-weight: bold">
          <tr>
            <td width="100">Nomor Ujian</td>
            <td width="10">:</td>
            <td>{{ $siswa->noujian }}</td>
          </tr>
          <tr>
            <td width="100">Nama</td>
            <td width="10">:</td>
            <td>{{ $siswa->nama }}</td>
          </tr>
          <tr>
            <td width="100">NISN</td>
            <td width="10">:</td>
            <td>{{ $siswa->nik }}</td>
          </tr>
          <tr>
            <td width="100">Kelas/Jurusan/Tingkat</td>
            <td width="10">:</td>
            <td>{{ $siswa->kelas->nama.'/'.($siswa->kelas->jurusan??'-').'/'.$siswa->kelas->tingkat }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Informasi Hasil Ujian</h4>
        <p class="card-category">Hasil ujian yang telah diikuti.</p>
      </div>
      @php
        $ujian = $siswa->login;
        $jadwal = $ujian->jadwal;
        $mapel = '';

        $getMapel = App\Models\Mapel::whereHas('soal',function($q) use($jadwal){
          $q->whereIn('uuid',json_decode($jadwal->soal));
        })
        ->orderBy('id','asc')
        ->select('nama')
        ->get();

        if (count($getMapel)) {
          foreach ($getMapel as $key => $m) {
            $mapel .= $m->nama;
            if ($key < count($getMapel)-2) {
              $mapel .= ', ';
            }elseif ($key == count($getMapel)-2) {
              if (count($getMapel) > 2) {
                $mapel .= ',';
              }
              $mapel .= ' dan ';
            }
          }
        }
      @endphp
      <div class="card-body">
        <table class="table" style="font-weight: bold">
          <tr>
            <td width="150">Nama Ujian</td>
            <td width="10">:</td>
            <td>{{ $ujian->jadwal->nama_ujian }}</td>
          </tr>
          <tr>
            <td width="150">Mata Pelajaran</td>
            <td width="10">:</td>
            <td>{{ $mapel }}</td>
          </tr>
          <tr>
            <td width="150">Jumlah Soal</td>
            <td width="10">:</td>
            <td>{{ ($ujian->soal_ujian?count(json_decode($ujian->soal_ujian)):$ujian->jadwal->jumlah_soal).' Soal' }}</td>
          </tr>
          <tr>
            <td width="150">Total Bobot</td>
            <td width="10">:</td>
            <td>{{ $ujian->jadwal->bobot }}</td>
          </tr>
          <tr>
            <td width="150">Bobot Per Soal</td>
            <td width="10">:</td>
            <td>{{ round($ujian->jadwal->bobot/$ujian->jadwal->jumlah_soal,2) }}</td>
          </tr>
          @php
            $terjawab = $siswa->login->soal_ujian?App\Models\Tes::where('noujian',$siswa->noujian)->where('pin',$siswa->login->pin)->whereIn('soal_item',json_decode($siswa->login->soal_ujian))->whereNotNull('jawaban')->count():0;
          @endphp
          <tr>
            <td width="150">Terjawab</td>
            <td width="10">:</td>
            <td>{{ $terjawab.' Soal' }}</td>
          </tr>
          <tr>
            <td width="150">Tidak Dijawab</td>
            <td width="10">:</td>
            <td>{{ ($ujian->soal_ujian?(count(json_decode($ujian->soal_ujian)))-$terjawab:$ujian->jadwal->jumlah_soal).' Soal' }}</td>
          </tr>
          @if ($ujian->jadwal->tampil_nilai=='Y')
            <tr>
              <td width="150">Nilai Akhir</td>
              <td width="10">:</td>
              <td>{{ $ujian->jadwal->jenis_soal=='E'?'Nilai segera diperiksa':$nilai }}</td>
            </tr>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
