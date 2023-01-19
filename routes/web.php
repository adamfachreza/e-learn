<?php

// Routing Admin
Route::post('/tambahAdmin','controllerAdmin@tambahAdmin');

Route::post('/loginAdmin','controllerAdmin@loginAdmin');

Route::post('/hapusAdmin','controllerAdmin@hapusAdmin');

Route::post('/listAdmin','controllerAdmin@listAdmin');
Route::post('/ubahAdmin','controllerAdmin@ubahAdmin');

// Routing Content
Route::post('/tambahContent','controllerContent@tambahContent');
Route::post('/ubahContent','controllerContent@ubahContent');
Route::post('/hapusContent','controllerContent@hapusContent');
Route::post('/listContent','controllerContent@listContent');
Route::post('/listContentPeserta','controllerContent@listContentPeserta');

Route::post('/register','controllerPeserta@register');

Route::post('/loginPeserta','controllerPeserta@loginPeserta');

Route::post('/listSoal','controllerUjian@listSoal');
Route::post('/jawab','controllerUjian@jawab');
Route::post('/hitungSkor','controllerUjian@hitungSkor');
Route::post('/selesaiUjian','controllerUjian@selesaiUjian');
Route::post('/cariContent', 'controllerContent@cariContent');
