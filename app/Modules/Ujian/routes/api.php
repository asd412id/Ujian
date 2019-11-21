<?php

Route::group(['module' => 'Ujian', 'middleware' => ['api'], 'namespace' => 'App\Modules\Ujian\Controllers'], function() {

    Route::resource('Ujian', 'UjianController');

});
