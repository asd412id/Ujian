<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    @if ($sekolah&&is_file(base_path('uploads/'.$sekolah->logo)))
      <link rel="icon" href="{{ url('uploads/'.$sekolah->logo) }}" type="image/x-icon"/>
      <link rel="shortcut icon" href="{{ url('uploads/'.$sekolah->logo) }}" type="image/x-icon"/>
    @endif
    <title>
        Masuk Sebagai Administrator
    </title>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/login.css') }}" />
    <style media="screen">
      .logo-wrap{
        text-align: center;
        padding: 30px;
        margin-bottom: -15px;
      }
      .logo-wrap img{
        width: 75px;
        height: auto;
      }
      form h1{
        padding: 15px;
      }
    </style>
</head>
<body>

<form method="post" action="{{ route('admin.dologin') }}">
  @if($sekolah)
  <div class="logo-wrap">
    @if (is_file(base_path('uploads/'.$sekolah->logo)))
      <img src="{{ url('uploads/'.$sekolah->logo) }}" alt="" style="position: relative">
    @endif
  </div>
  @endif
  {{ csrf_field() }}
  <h1>ADMINISTRATOR LOGIN</h1>
  <div class="inset">
  @if ($errors->any())
    <div style="padding: 0 0 15px;font-weight: bold;color: red">
      @foreach ($errors->all() as $key => $e)
        <p style="text-align: center">{{ $e }}</p>
      @endforeach
    </div>
  @endif
  <p>
    <label for="email">USERNAME</label>
    <input type="text" name="username" value="{{ old('username') }}" autocomplete="off">
  </p>
  <p>
    <label for="password">PASSWORD</label>
    <input type="password" name="password">
  </p>
  <p>
    <input type="checkbox" name="remember" id="remember">
    <label for="remember" {{ old('remember')=='on'?'checked':'' }} style="position: relative; top: -5px">Remember me</label>
  </p>
  </div>
  <p class="p-container">
    <input type="submit" name="go" id="go" value="Masuk">
  </p>
</form>
<div style="text-align: center">
  @if ($sekolah)
    <p>Aplikasi Ujian {{ $sekolah->nama }}</p>
    <p>{{ $sekolah->alamat }}</p>
    <p>{{ 'Kab. '.$sekolah->kota.', Propinsi '.$sekolah->propinsi }}</p>
    <p>{{ 'Telp: '.$sekolah->telp.', Fax: '.$sekolah->fax }}</p>
  @endif
  <p>&copy; {{ date('Y') }} by <a style="color: #fff" target="_blank" href="https://www.facebook.com/aezdar">Asdar Bin Syam</a></p>
</div>
</body>
</html>
