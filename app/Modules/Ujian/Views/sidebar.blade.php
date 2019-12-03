<div class="sidebar" data-color="purple" data-background-color="white" data-image="{{ url('/') }}/assets/img/sidebar-1.jpg">
  <!--
  Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

  Tip 2: you can also add an image using data-image tag
-->
  <div class="logo">
    <a href="#" class="simple-text logo-normal">
      Selamat Datang
      <h4>{{ $siswa->nama }}</h4>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      @if (Request::url() == route('ujian.cekdata'))
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" style="font-size: 1em">
            Silahkan cek kembali data ujian Anda!
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" style="font-size: 1em">
            Apabila terdapat kesalahan data (Nomor Ujian) dan Sesi Ujian, Silahkan hubungi operator untuk memperbaikinya sebelum ujian dimulai.
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" style="font-size: 1em">
            <span class="text-danger" style="font-weight:bold;text-transform: uppercase !important">Perhatian!!! Jangan pernah login pada lebih dari 1 (satu) perangkat atau sesi ujian Anda akan berakhir!</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" style="font-size: 1em">
            Jika semua data sudah benar, silahkan klik tombol "Mulai Ujian" untuk memulai ujian!
          </a>
        </li>
      @elseif (Request::url() == route('ujian.tes'))
        <li class="nav-item active text-center">
          <a class="nav-link" href="javascript:void(0)" style="font-size: 1em">
            <span class="timer" style="font-weight: bold;font-size: 2em;line-height: 1.2em">00:00:00</span>
          </a>
        </li>
        <li class="nav-item text-center s-left">
          <a href="{{ route('ujian.selesai') }}" class="btn btn-danger" onclick="return confirm($(this).data('text'))" style="color: #fff" data-text="Ujian akan selesai dan jawaban tidak dapat diubah kembali! ANDA YAKIN?" data-url="{{ route('ujian.selesai') }}">Selesai Ujian</a>
          </a>
        </li>
        <li style="margin-top: 45px">
          <a href="javascript:void(0)" style="font-size: 1em;padding: 0;font-weight: bold">Nomor Soal:</a>
        </li>
        @foreach (json_decode($soal) as $key => $s)
          @php
            $tes = \App\Models\Tes::where('soal_item',$s)
            ->where('noujian',Auth::guard('siswa')->user()->noujian)
            ->where('pin',Auth::guard('siswa')->user()->login->pin)
            ->first();
          @endphp
          <li class="nav-soal {{ $key==0?'active':'' }}">
            <a class="btn btn-sm btn-{{ !is_null(@$tes->jawaban)?'success':'default' }} btn-soal" id="soal-{{ $s }}" data-soal="{{ $s }}" data-key="{{ $key+1 }}" href="javascript:void(0)">
              {{ $key+1 }}
            </a>
          </li>
        @endforeach
      @elseif (Request::url() == route('ujian.nilai'))
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" style="font-size: 1em">
            Selamat, Anda telah selesai melaksanakan ujian <strong>{{ $siswa->login->jadwal->nama_ujian }}</strong><br><br>
            Pada Mata Pelajaran:
            <strong>{{ $mapel }}</strong><br><br>

            Berikut hasil ujian yang telah Anda ikuti.
          </a>
        </li>
        <li class="nav-item active">
          <a class="nav-link text-center" href="{{ route('ujian.logout') }}">
            Keluar
          </a>
        </li>
      @endif
    </ul>
  </div>
</div>
