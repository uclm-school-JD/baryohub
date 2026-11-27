<?php

use App\Http\Controllers\Admin\CapstoneController;
use App\Http\Controllers\ResidentServicesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','verified'])->group(function(){
    Route::get('/resident-services',[ResidentServicesController::class,'index'])->name('resident-services.index');
    Route::post('/resident-services/profile',[ResidentServicesController::class,'saveProfile'])->name('resident-services.profile');
    Route::post('/resident-services/incidents',[ResidentServicesController::class,'report'])->name('resident-services.incidents');
    Route::post('/resident-services/notifications/read',[ResidentServicesController::class,'readNotices'])->name('resident-services.read');
    Route::get('/resident-services/card',[ResidentServicesController::class,'card'])->name('resident-services.card');
    Route::prefix('admin/capstone')->name('capstone.admin.')->middleware('can:manage-staff-access')->group(function(){
        Route::get('/',[CapstoneController::class,'index'])->name('index');
        Route::patch('/residents/{user}/approval',[CapstoneController::class,'approval'])->name('approval');
        Route::patch('/residents/{user}/indigent',[CapstoneController::class,'indigent'])->name('indigent');
        Route::post('/officials',[CapstoneController::class,'official'])->name('official');
        Route::patch('/officials/{official}',[CapstoneController::class,'updateOfficial'])->name('official.update');
        Route::patch('/incidents/{incident}',[CapstoneController::class,'incident'])->name('incident');
        Route::get('/reports/csv',[CapstoneController::class,'export'])->name('export');
    });
});
