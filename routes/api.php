<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/* Route::get('/testRoute', function (Request $request) {
    return "test";
}); */

Route::get('/students', [DataController::class, 'getStudents'])->name('students-data');
Route::get('/rooms', [DataController::class, 'getRooms'])->name('rooms-data');

