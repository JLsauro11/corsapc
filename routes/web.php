<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    return view('index');
});

Route::controller(HomeController::class)->group(function() {
    Route::get('home', 'index')->name('home.index');
    Route::post('vehicle/validate', 'validatePdf')->name('vehicle.pdf.validate');
    Route::post('vehicle/generate', 'generatePdf')->name('vehicle.pdf.generate');

});