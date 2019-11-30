@extends('Ujian::layout')
@section('content')
<div class="row">
  <div class="col-md-12 warning text-center">
    <span class="text-danger" style="font-weight:bold;text-transform: uppercase !important">Perhatian!!!<br>Jangan pernah login pada lebih dari 1 (satu) perangkat atau sesi ujian Anda akan berakhir!</span>
  </div>
  <div class="col-md-6">
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
  <div class="col-md-6">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Informasi Ujian</h4>
        <p class="card-category">Informasi ujian yang akan diikuti.</p>
      </div>
      @php
        $ujian = $siswa->login;
      @endphp
      <div class="card-body">
        <table class="table" style="font-weight: bold">
          <tr>
            <td width="150">Nama Ujian</td>
            <td width="10">:</td>
            <td>{{ $ujian->jadwal->nama_ujian }}</td>
          </tr>
          <tr>
            <td width="150">Jenis Soal</td>
            <td width="10">:</td>
            <td>{{ $ujian->jadwal->jenis_soal=='P'?'Pilihan Ganda':'Essay' }}</td>
          </tr>
          <tr>
            <td width="150">Jumlah Soal</td>
            <td width="10">:</td>
            <td>{{ $ujian->jadwal->jumlah_soal }}</td>
          </tr>
          <tr>
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
          <tr>
            <td width="150">Lama Ujian</td>
            <td width="10">:</td>
            <td>{{ $ujian->jadwal->lama_ujian.' Menit' }}</td>
          </tr>
          <tr>
            <td width="150">Ujian Dimulai</td>
            <td width="10">:</td>
            <td>{{ date('d/m/Y - H:i',strtotime($ujian->jadwal->mulai_ujian)) }}</td>
          </tr>
          <tr>
            <td width="150">Ujian Selesai</td>
            <td width="10">:</td>
            <td>{{ date('d/m/Y - H:i',strtotime($ujian->jadwal->selesai_ujian)) }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="row" style="margin-top: -30px">
  <div class="col-md-12">
    <div class="card-body text-center">
      @if (!$siswa->login->start && !$siswa->login->end)
        {{-- <a href="javascript:void(0)" data-url="{{ route('ujian.tes') }}" class="btn btn-lg btn-danger confirm" data-text="Waktu akan mulai berjalan!<br>Mulai ujian?">Mulai Ujian <i class="material-icons">send</i></a> --}}
        <a href="{{ route('ujian.tes') }}" class="btn btn-lg btn-danger native-confirm" data-text="Waktu akan mulai berjalan! MULAI UJIAN?">Mulai Ujian <i class="material-icons">send</i></a>
      @else
        @if ($siswa->login->end)
          <h4 class="text-center alert alert-primary">Ujian Telah Selesai!</h4>
          <a href="{{ route('ujian.nilai') }}" class="btn btn-lg btn-success">Lihat Nilai <i class="material-icons">send</i></a>
        @else
          <h4 class="text-center">Anda sudah memulai ujian!</h4>
          <a href="{{ route('ujian.tes') }}" class="btn btn-lg btn-warning">Kembali <i class="material-icons">send</i></a>
        @endif
      @endif
    </div>
  </div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
  $(".native-confirm").click(function(){
    return confirm($(this).data('text'));
  })
</script>
@endsection
