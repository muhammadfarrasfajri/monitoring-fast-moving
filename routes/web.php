<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
	DashboardController,
	StatusrunningmaterialController,
	PrpooutstandingController,
	KontrakController,
	UserController,
	TrendController
};

Route::redirect('/', '/login');

Route::group([
	'middleware' => 'auth',
	'prefix' => 'admin',
	'as' => 'admin.',
], function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/logs', [DashboardController::class, 'activity_logs'])->name('logs');
	Route::post('/logs/delete', [DashboardController::class, 'delete_logs'])->name('logs.delete');
	Route::post('trend/upload-csv', [TrendController::class, 'upload'])->name('trend.upload.csv.post');
	Route::post('/trend/delete-all', [TrendController::class, 'deleteAll'])->name('trend.delete-all');

	// Settings menu
	Route::view('/settings', 'admin.settings')->name('settings');
	Route::post('/settings', [DashboardController::class, 'settings_store'])->name('settings');

	// Profile menu
	Route::view('/profile', 'admin.profile')->name('profile');
	Route::post('/profile', [DashboardController::class, 'profile_update'])->name('profile');
	Route::post('/profile/upload', [DashboardController::class, 'upload_avatar'])->name('profile.upload');

	// Member menu
	Route::get('/petugas', [UserController::class, 'index'])->name('member');
	Route::get('/petugas/create', [UserController::class, 'create'])->name('member.create');
	Route::post('/petugas/create', [UserController::class, 'store'])->name('member.create');
	Route::get('/petugas/{id}/edit', [UserController::class, 'edit'])->name('member.edit');
	Route::post('/petugas/{id}/update', [UserController::class, 'update'])->name('member.update');
	Route::post('/petugas/{id}/delete', [UserController::class, 'destroy'])->name('member.delete');

	//Statusrunningmaterial
	Route::get('/status-running-material', [StatusrunningmaterialController::class, 'index'])->name('status-running-material.index');
	Route::get('status-running-material/download-excel', [StatusrunningmaterialController::class, 'downloadExcel'])->name('download.excel');
	Route::get('status-running-material/data', [StatusRunningMaterialController::class, 'getData'])->name('status-running-material.data');
	Route::get('/status-running-material/qoh-percentage', [StatusRunningMaterialController::class, 'calculateQOHPercentage'])->name('status-running-material.qoh-percentage');
	Route::post('/status-running-material/send-email', [StatusrunningmaterialController::class, 'store'])->name('status-running-material.send-email');


	//PR / PO Outstanding 
	Route::get('/pr-po-outstanding', [PrpooutstandingController::class, 'index'])->name('pr-po-outstanding.index');
	Route::post('pr-po-outstanding/upload-csv', [PrpooutstandingController::class, 'upload'])->name('prpo.upload.csv.post');
	Route::post('/pr-po-outstanding/delete-all', [PrpooutstandingController::class, 'deleteAll'])->name('pr-po-outstanding.delete-all');

	//trend kategori-A
	Route::get('/kontrak', [KontrakController::class, 'index'])->name('kontrak.index');
	Route::get('/kontrak/data', [KontrakController::class, 'getData'])->name('kontrak.data');






});


require __DIR__ . '/auth.php';
