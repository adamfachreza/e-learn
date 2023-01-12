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
