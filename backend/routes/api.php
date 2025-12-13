<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('patients', PatientController::class);

Route::get('/hospitals', function () {
    return App\Models\Hospital::all();
});

Route::get('/tutors', function () {
    return App\Models\Tutor::all();
});