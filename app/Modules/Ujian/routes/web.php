<?php

Route::group(['module' => 'Ujian', 'middleware' => ['web'], 'namespace' => 'App\Modules\Ujian\Controllers'], function() {

    Route::get('/','UjianController@index')->name('ujian.login')->middleware('guest:siswa');
    Route::post('/','UjianController@login')->name('ujian.dologin')->middleware('guest:siswa');

    Route::group(['prefix'=>'ujian','middleware'=>['auth:siswa','login:siswa']], function()
    {
      Route::get('/tokengenerate', function()
      {
        return csrf_token();
      })->name('token.ujian.generate');
      Route::get('/cekdata','UjianController@cekData')->name('ujian.cekdata');
      Route::get('/tes','UjianController@tes')->name('ujian.tes')->middleware('ujian:siswa');
      Route::get('/selesai','UjianController@selesai')->name('ujian.selesai');
      Route::get('/nilai','UjianController@nilai')->name('ujian.nilai');
      Route::post('/submit/{uuid}','UjianController@submit')->name('ujian.submit');
      Route::get('/audiorepeat','UjianController@audioRepeat')->name('ujian.audiorepeat');
      Route::get('ujian/getsoal','UjianController@getsoal')->name('ujian.getsoal')->middleware('shortcode');
      Route::get('/logout','UjianController@logout')->name('ujian.logout');
    });

});
