<?php

// Routing Admin
Route::post('/tambahAdmin','controllerAdmin@tambahAdmin');

Route::post('/loginAdmin','controllerAdmin@loginAdmin');

Route::post('/hapusAdmin','controllerAdmin@hapusAdmin');

Route::post('/listAdmin','controllerAdmin@listAdmin');

// Routing Content
Route::post('/tambahContent','controllerContent@tambahContent');
Route::post('/ubahContent','controllerContent@ubahContent');
Route::post('/hapusContent','controllerContent@hapusContent');
