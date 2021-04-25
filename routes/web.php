<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Global routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'users'], function() {
  Route::get('/{name}/{discrim}', [UserController::class, 'show'])->name('users.show');
});
Route::get('/developers', [UserController::class, 'devs'])->name('users.devs');



// Guest routes
Route::group(['middleware' => 'guest'], function() {
  Route::get('/login', [AuthController::class, 'login'])->name('login');
  Route::get('/login/callback', [AuthController::class, 'callback'])->name('login.callback');
});



// Logged in user routes
Route::group(['middleware' => 'auth'], function() {
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

  // Setting Routes
  Route::group(['prefix' => 'settings'], function() {
    Route::get('/', [SettingsController::class, 'index']);
  });

  // Developer Routes
  Route::group(['middleware' => ['role:dev']], function() {

    Route::put('/pin/{id}', [CommentController::class, 'pin'])->name('pin');

    Route::group(['prefix' => 'addon'], function() {
      Route::post('/', [AddonController::class, 'store'])->name('addon.store');
    });
  });


  Route::put('/user', [AuthController::class, 'update'])->name('user.update');
  Route::delete('/user', [AuthController::class, 'destroy'])->name('user.destroy');
});



// Admin routes
Route::group(['middleware' => ['role:admin'], 'prefix' => 'admin'], function() {
  Route::redirect('/', '/admin/users');

  Route::get('/users', [AdminController::class, 'users']);
  Route::put('/user', [AdminController::class, 'update']);

  Route::get('/addons', [AdminController::class, 'addons']);
});



// Addon routes
Route::put('/like/{id}', [LikeController::class, 'like'])->middleware('auth');
Route::put('/download/{id}', [AddonController::class, 'download']);

Route::group(['prefix' => '{type}'], function() {
  Route::get('/', [AddonController::class, 'index'])->name('addon.index');

  Route::group(['prefix' => '{name}'], function() {
    Route::get('/', [AddonController::class, 'show'])->name('addon.show');
  });
  
  // Developer addon routes
  Route::group(['middleware' => ['role:dev', 'auth'], 'prefix' => '{name}'], function() {
    Route::put('/', [AddonController::class, 'update'])->name('addon.update');
    Route::delete('/', [AddonController::class, 'destroy'])->name('addon.destroy');
  });
});