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
      @endphp
      <div class="card-body">
        <table class="table" style="font-weight: bold">
          <tr>
            <td width="150">Nama Ujian</td>
            <td width="10">:</td>
            <td>{{ $ujian->jadwal->getSoal->nama }}</td>
          </tr>
          <tr>
            <td width="150">Jumlah Soal</td>
            <td width="10">:</td>
            <td>{{ count($ujian->jadwal->getSoal->item).' Soal' }}</td>
          </tr>
          @php
            $terjawab = App\Models\Tes::where('noujian',$siswa->noujian)->where('pin',$siswa->login->pin)->whereNotNull('jawaban')->count();
          @endphp
          <tr>
            <td width="150">Terjawab</td>
            <td width="10">:</td>
            <td>{{ $terjawab.' Soal' }}</td>
          </tr>
          <tr>
            <td width="150">Tidak Dijawab</td>
            <td width="10">:</td>
            <td>{{ (count($ujian->jadwal->getSoal->item))-$terjawab.' Soal' }}</td>
          </tr>
          @if ($ujian->jadwal->tampil_nilai=='Y')
            <tr>
              <td width="150">Nilai Akhir</td>
              <td width="10">:</td>
              <td>{{ is_null($nilai)?'Nilai segera diperiksa':$nilai }}</td>
            </tr>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
