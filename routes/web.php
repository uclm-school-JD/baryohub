<?php

use App\Enums\DocumentRequestStatus;
use App\Http\Controllers\Admin\BarangayUpdateController as AdminBarangayUpdateController;
use App\Http\Controllers\Admin\StaffAccessController;
use App\Http\Controllers\BarangayUpdateController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\Office\DocumentRequestController as OfficeDocumentRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $requests = $request->user()->documentRequests();

        return view('dashboard', [
            'pendingRequests' => (clone $requests)->where('status', DocumentRequestStatus::Pending->value)->count(),
            'readyRequests' => $requests->where('status', DocumentRequestStatus::ReadyForPickup->value)->count(),
        ]);
    })->name('dashboard');

    Route::get('/requests', [DocumentRequestController::class, 'index'])->name('requests.index');
    Route::post('/requests', [DocumentRequestController::class, 'store'])->name('requests.store');
    Route::get('/barangay-updates', [BarangayUpdateController::class, 'index'])->name('barangay-updates.index');

    Route::prefix('office')->name('office.')->middleware('can:process-document-requests')->group(function () {
        Route::get('/requests', [OfficeDocumentRequestController::class, 'index'])->name('requests.index');
        Route::patch('/requests/{documentRequest}', [OfficeDocumentRequestController::class, 'update'])->name('requests.update');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware('can:manage-staff-access')->group(function () {
            Route::get('/staff', [StaffAccessController::class, 'index'])->name('staff.index');
            Route::patch('/staff/{user}', [StaffAccessController::class, 'update'])->name('staff.update');
        });

        Route::middleware('can:publish-barangay-updates')->group(function () {
            Route::get('/barangay-updates', [AdminBarangayUpdateController::class, 'index'])->name('barangay-updates.index');
            Route::post('/barangay-updates', [AdminBarangayUpdateController::class, 'store'])->name('barangay-updates.store');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

require __DIR__.'/capstone.php';
