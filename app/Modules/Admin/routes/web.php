<?php

Route::group(['module' => 'Admin', 'middleware' => ['web'], 'namespace' => 'App\Modules\Admin\Controllers'], function() {

  Route::group(['prefix'=>'admin'],function(){
    Route::get('/','AdminController@login')->name('admin.login')->middleware('guest:admin');
    Route::post('/dologin','AdminController@dologin')->name('admin.dologin');
    Route::get('/logout','AdminController@logout')->name('admin.logout');

    Route::group(['middleware'=>['auth:admin']], function()
    {
      Route::group(['prefix'=>'reset'], function()
      {
        Route::get('/','AdminController@reset')->name('admin.reset.index');
        Route::get('/{data}','AdminController@resetData')->name('admin.reset.data');
      });

      Route::post('/import','ImportController@import')->name('sekolah.import');
      Route::post('/import-soal','ImportController@importSoal')->name('import.soal');

      Route::get('/dashboard','AdminController@index')->name('admin.index');
      Route::get('/profil','AdminController@profile')->name('admin.profile');
      Route::get('/download-template/{type}','AdminController@downloadTemplate')->name('download.template');
      Route::post('/profil/update','AdminController@profileUpdate')->name('admin.profile.update');
      Route::get('/media','AdminController@media')->name('admin.media');

      Route::group(['prefix'=>'sekolah'],function(){
        Route::get('/','SekolahController@index')->name('sekolah.index');
        Route::post('/store','SekolahController@store')->name('sekolah.store');
        Route::post('/upload-logo/{type}','SekolahController@uploadLogo')->name('sekolah.upload.logo');
      });

      Route::group(['prefix'=>'master'],function(){
        Route::group(['prefix'=>'kelas'], function()
        {
          Route::get('/','KelasController@index')->name('master.kelas.index');
          Route::get('/create','KelasController@create')->name('master.kelas.create');
          Route::post('/store','KelasController@store')->name('master.kelas.store');
          Route::get('/edit/{uuid}','KelasController@edit')->name('master.kelas.edit');
          Route::post('/update/{uuid}','KelasController@update')->name('master.kelas.update');
          Route::get('/destroy/{uuid}','KelasController@destroy')->name('master.kelas.destroy');
        });

        Route::group(['prefix'=>'mapel'], function()
        {
          Route::get('/','MapelController@index')->name('master.mapel.index');
          Route::get('/create','MapelController@create')->name('master.mapel.create');
          Route::post('/store','MapelController@store')->name('master.mapel.store');
          Route::get('/edit/{uuid}','MapelController@edit')->name('master.mapel.edit');
          Route::post('/update/{uuid}','MapelController@update')->name('master.mapel.update');
          Route::get('/destroy/{uuid}','MapelController@destroy')->name('master.mapel.destroy');
        });

        Route::group(['prefix'=>'siswa'], function()
        {
          Route::get('/','SiswaController@index')->name('master.siswa.index');
          Route::get('/create','SiswaController@create')->name('master.siswa.create');
          Route::post('/store','SiswaController@store')->name('master.siswa.store');
          Route::get('/edit/{uuid}','SiswaController@edit')->name('master.siswa.edit');
          Route::post('/update/{uuid}','SiswaController@update')->name('master.siswa.update');
          Route::get('/destroy/{uuid}','SiswaController@destroy')->name('master.siswa.destroy');
        });
      });

      Route::group(['prefix'=>'soal'], function()
      {
        Route::get('/','SoalController@index')->name('soal.index');
        Route::get('/create','SoalController@create')->name('soal.create');
        Route::post('/store','SoalController@store')->name('soal.store');
        Route::get('/edit/{uuid}','SoalController@edit')->name('soal.edit');
        Route::post('/update/{uuid}','SoalController@update')->name('soal.update');
        Route::get('/destroy/{uuid}','SoalController@destroy')->name('soal.destroy');

        Route::group(['prefix'=>'detail'], function()
        {
          Route::get('/{uuid}','SoalController@detail')->name('soal.detail');
          Route::get('/create/{uuid}','SoalController@itemCreate')->name('soal.item.create');
          Route::post('/store/{uuid}','SoalController@itemStore')->name('soal.item.store');
          Route::get('/edit/{uuid}','SoalController@itemEdit')->name('soal.item.edit');
          Route::post('/update/{uuid}','SoalController@itemUpdate')->name('soal.item.update');
          Route::get('/destroy/{uuid}','SoalController@itemDestroy')->name('soal.item.destroy');
          Route::get('/show/{uuid}','SoalController@itemShow')->name('soal.item.show')->middleware('shortcode');
        });

      });

      Route::group(['prefix'=>'jadwal'], function()
      {
        Route::get('/','JadwalUjianController@index')->name('jadwal.ujian.index');
        Route::get('/create','JadwalUjianController@create')->name('jadwal.ujian.create');
        Route::post('/store','JadwalUjianController@store')->name('jadwal.ujian.store');
        Route::get('/activate/{uuid}','JadwalUjianController@activate')->name('jadwal.ujian.activate');
        Route::get('/reset/{uuid}','JadwalUjianController@reset')->name('jadwal.ujian.reset');
        Route::get('/edit/{uuid}','JadwalUjianController@edit')->name('jadwal.ujian.edit');
        Route::post('/update/{uuid}','JadwalUjianController@update')->name('jadwal.ujian.update');
        Route::get('/destroy/{uuid}','JadwalUjianController@destroy')->name('jadwal.ujian.destroy');
        Route::get('/cetak/kartu/{uuid}','JadwalUjianController@printKartu')->name('jadwal.ujian.print.kartu');
        Route::get('/cetak/absen/{uuid}','JadwalUjianController@printAbsen')->name('jadwal.ujian.print.absen');
        Route::get('/ajax/get-peserta','JadwalUjianController@getPeserta')->name('jadwal.ujian.ajax.getpeserta');
        Route::get('/ajax/get-soal','JadwalUjianController@getSoal')->name('jadwal.ujian.ajax.getsoal');
      });

      Route::group(['prefix'=>'monitoring'], function()
      {
        Route::get('/','JadwalUjianController@monitoring')->name('jadwal.ujian.monitoring');
        Route::get('/detail/{uuid}','JadwalUjianController@monitoringDetail')->name('jadwal.ujian.monitoring.detail');
        Route::get('/getdata/{uuid}','JadwalUjianController@monitoringGetData')->name('jadwal.ujian.monitoring.getdata');
        Route::get('/reset/{pin}/{noujian}','JadwalUjianController@monitoringReset')->name('jadwal.ujian.reset');
        Route::get('/stop/{pin}/{noujian}','JadwalUjianController@monitoringStop')->name('jadwal.ujian.stop');
      });

      Route::group(['prefix'=>'nilai'], function()
      {
        Route::get('/','NilaiController@index')->name('nilai.index');
        Route::get('/{uuid}/detail','NilaiController@detail')->name('nilai.detail');
        Route::get('/detail/{jadwal}.{siswa}','NilaiController@detailDownload')->name('nilai.detail.download');
        Route::get('/download/excel/{uuid}','NilaiController@downloadExcel')->name('nilai.download.excel');
        Route::get('/download/pdf/{uuid}','NilaiController@downloadPDF')->name('nilai.download.pdf');
      });
    });

  });

});
