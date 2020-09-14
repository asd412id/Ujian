<div class="sidebar" data-color="purple" data-background-color="black" data-image="{{ url('/') }}/assets/img/sidebar-1.jpg">
  <!--
  Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

  Tip 2: you can also add an image using data-image tag
-->
  <div class="logo">
    <a href="#" class="simple-text logo-normal">
      Administrator
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      {{-- <li class="nav-item{{ Request::url()==route('admin.index')?' active':'' }}">
        <a class="nav-link" href="{{ route('admin.index') }}">
          <i class="material-icons">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li> --}}
      <li class="nav-item{{ strpos(Request::url(),route('sekolah.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('sekolah.index') }}">
          <i class="material-icons">school</i>
          <p>Sekolah</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('master.kelas.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('master.kelas.index') }}">
          <i class="material-icons">meeting_room</i>
          <p>Master Kelas</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('master.mapel.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('master.mapel.index') }}">
          <i class="material-icons">library_books</i>
          <p>Master Mata Pelajaran</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('master.siswa.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('master.siswa.index') }}">
          <i class="material-icons">group</i>
          <p>Master Siswa</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('soal.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('soal.index') }}">
          <i class="material-icons">folder</i>
          <p>Bank Soal</p>
        </a>
      </li>
      <li class="nav-item{{ Request::url()==route('admin.media')?' active':'' }}">
        <a class="nav-link" href="{{ route('admin.media') }}">
          <i class="material-icons">perm_media</i>
          <p>Media</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('jadwal.ujian.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('jadwal.ujian.index') }}">
          <i class="material-icons">watch_later</i>
          <p>Jadwal Ujian</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('jadwal.ujian.monitoring'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('jadwal.ujian.monitoring') }}">
          <i class="material-icons">desktop_mac</i>
          <p>Monitoring Ujian</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('jadwal.ujian.reqreset'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('jadwal.ujian.reqreset') }}">
          <i class="material-icons">refresh</i>
          <p>Reset Login <span style="position: relative;top: -3px;padding: 3px 13px !important;font-size: 1.2em" class="pull-right btn btn-xs btn-danger d-none" id="reset-notif"></span></p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('nilai.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('nilai.index') }}">
          <i class="fa fa-edit"></i>
          <p>Nilai Ujian</p>
        </a>
      </li>
      <li class="nav-item{{ strpos(Request::url(),route('admin.reset.index'))!==false?'  active':'' }}">
        <a class="nav-link" href="{{ route('admin.reset.index') }}">
          <i class="text-danger fa fa-exclamation-triangle"></i>
          <p>Reset Data</p>
        </a>
      </li>
    </ul>
  </div>
</div>
