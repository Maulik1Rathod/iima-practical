<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatAllocationController;

Route::get('/', function () {
    return redirect()->route('seatAllocation');
});

Route::get('seatAllocation',[SeatAllocationController::class,'seatAllocation'])->name('seatAllocation');

Route::post('storeData',[SeatAllocationController::class,'storeData'])->name('storeData');


