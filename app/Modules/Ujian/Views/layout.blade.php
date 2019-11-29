<!doctype html>
<html lang="en">

<head>
  <title>{{ $title??'Ujian' }}</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  @php
    $sekolah = App\Models\Sekolah::first();
  @endphp
  @if ($sekolah)
    @if (is_file(base_path('uploads/'.$sekolah->logo)))
      <link rel="icon" href="{{ url('uploads/'.$sekolah->logo) }}" type="image/x-icon"/>
      <link rel="shortcut icon" href="{{ url('uploads/'.$sekolah->logo) }}" type="image/x-icon"/>
    @elseif (is_file(base_path('uploads/'.$sekolah->dept_logo)))
      <link rel="icon" href="{{ url('uploads/'.$sekolah->dept_logo) }}" type="image/x-icon"/>
      <link rel="shortcut icon" href="{{ url('uploads/'.$sekolah->dept_logo) }}" type="image/x-icon"/>
    @endif
  @endif
  <!--     Fonts and icons     -->
  <link href="{{ url('assets/css/fonts.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/css/font-awesome.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/fontawesome/css/all.min.css') }}" rel="stylesheet" />
  <!-- Material Kit CSS -->
  <link href="{{ url('assets/iconfont/material-icons.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/css/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/lightbox/css/lightbox.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/css/material-dashboard.css?v=2.1.1') }}" rel="stylesheet" />
  <style media="screen">
    .pagination{
      float: right;
    }
    .pagination .page-link{
      color: #9c27b0
    }
    .pagination .page-item.active .page-link{
      background: #9c27b0;
    }
    .btn-xs{
      padding: 0.4rem !important;
    }
    .table td .btn-xs{
      margin: 0 !important;
    }
    select.form-control {
        /* width: 268px; */
        /* padding: 5px;
        font-size: 16px;
        line-height: 1;
        border: 0;
        border-radius: 5px;
        height: 34px; */
        background: url({{ url('assets/img/br_down.png') }}) no-repeat right !important;
        /* -webkit-appearance: none; */
        border-bottom: solid 1px #999999;
        background-position-x: 240px;
        background-size: 11px !important;
    }
    select.form-control:focus{
      border-bottom: solid 2px #9c27b0;
    }
    .alert.animated{
      z-index: 1051 !important;
    }
    .text-bold{
      font-weight: bold !important;
    }
    .select2-results__option{
      display: block !important;
      text-align: left !important;
    }
    .select2-container{
      width: 100% !important;
    }
    button.btn.btn-flat {
        padding: 1rem;
        background: none;
        color: #333;
        border: none !important;
    }
    li.nav-soal{
      float: left;
      padding: 0 !important;
    }
    li.nav-soal a{
      width: 38px;
      padding: 10px 0 !important;
      text-align: center;
    }
    .main-panel>.content{
      margin-top: 25px;
    }
    .main-panel > .navbar .navbar-brand{
      white-space: normal;
      height: auto;
    }
    @if (Request::url() == route('ujian.tes'))
    @media screen and (min-width:800px) {
      .main-panel>.content{
        margin-top: -30px;
        padding-top: 0;
      }
    }
    @endif
    .card-body{
      position: relative;
    }
    .mask-container{
      display: none;
    }
    .mask{
      position: absolute;
      z-index: 100;
      left: 0;
      top: 0;
      right: 0;
      bottom: 0;
      background: rgba(255,255,255,.7);
      display: -webkit-flex;
      display: -ms-flexbox;
      display: flex;
      -webkit-align-items: center;
      -ms-flex-align: center;
      align-items: center;
    }
    .s-opsi{
      width: 40px;
    }
    .num{
      font-weight: bold;
      margin-bottom: 15px;
      font-size: 1.2em;
    }
    .warning{
      display: none;
    }
    .soal-title{
      display: none;
    }
    a.btn-soal {
      margin: 10px 7px 0 !important;
    }
    @media screen and (max-width: 800px) {
      .soal-title{
        display: block;
      }
      .s-left{
        display: none;
      }
      .warning{
        display: block;
      }
      .main-panel > .content{
        padding: 0;
        margin-top: 0;
      }
      .navbar-wrapper{
        width: 81%;
      }
      .main-panel > .navbar .navbar-brand{
        white-space: normal;
        height: auto;
      }
      .s-opsi{
        display: none;
      }
      .s-wrap{
        width: 100%;
      }
      #soal-wrapper{
        padding: 0 5px;
      }
      .copyright{
        float: none !important;
        text-align: center;
      }
    }
    .modal-content{
      padding: 0 15px;
    }
  </style>
  @yield('header')
</head>

<body>
  <div class="wrapper ">
    @include('Ujian::sidebar')
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:void(0)">{{ $breadcrumb??'Periksa Data' }}</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <div class="copyright float-right">
            &copy; {{ date('Y') }} Aplikasi Ujian by <a href="https://www.facebook.com/aezdar"> Asdar Bin Syam </a>
          </div>
          <script src="{{ url('/') }}/assets/js/core/jquery.min.js"></script>
          <script src="{{ url('/') }}/assets/js/core/popper.min.js"></script>
          <script src="{{ url('/') }}/assets/js/core/bootstrap-material-design.min.js"></script>
          <script src="{{ url('/') }}/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
          <script src="{{ url('/') }}/assets/plugins/lightbox/js/lightbox.min.js"></script>
          <script src="{{ url('/') }}/assets/js/plugins/bootstrap-notify.js"></script>
          <script src="{{ url('/') }}/assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
          <script src="{{ url('/') }}/assets/js/select2.min.js" type="text/javascript"></script>
          <script src="{{ url('/') }}/assets/js/moment.min.js" type="text/javascript"></script>
          <script src="{{ url('/') }}/assets/js/bootstrap-material-datetimepicker.js" type="text/javascript"></script>
          <script type="text/javascript">
          $(document).ready(function(){
            $(".confirm").click(function(e){
              e.preventDefault();
              var confirm = `
                <h3 class="text-center">`+$(this).data('text')+`</h3>
                <div class="text-center" style="margin-bottom: 15px">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                  <a href="`+$(this).data('url')+`" class="btn btn-danger">Ya</a>
                </div>
              `;
              $(".modal-confirm").find('.modal-content').html(confirm);
              $(".modal-confirm").modal({
                show: true
              });
            })
          })
          </script>
          <div class="modal fade modal-confirm" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content"></div>
            </div>
          </div>
          @yield('footer')
        </div>
      </footer>
    </div>
  </div>
</body>

</html>
