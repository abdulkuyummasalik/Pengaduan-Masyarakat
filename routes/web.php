<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserController;


Route::get('/test', [TestController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('landing-page');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/postLogin', [AuthController::class, 'postLogin'])->name('postLogin');
});


Route::middleware('auth')->group(function () {
    Route::prefix('report')->name('report.')->group(function () {
        Route::prefix('article')->name('article.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/create', [ReportController::class, 'create'])->name('create');
            Route::delete('/delete/{id}', [ReportController::class, 'delete'])->name('delete');
            Route::get('/show/{id}', [ReportController::class, 'show'])->name('show');
            Route::post('/vote/{id}', [ReportController::class, 'vote'])->name('vote');
            Route::post('/store', [ReportController::class, 'store'])->name('store');
            Route::get('/me', [ReportController::class, 'me'])->name('me');
            Route::post('/comment/{id}', [CommentController::class, 'createComment'])->name('comment.store');
        });
    });

    Route::prefix('response')->middleware('Staff')->name('response.')->group(function () {
        Route::get('/report', [ResponseController::class, 'index'])->name('index');
        Route::get('/report/export', [ResponseController::class, 'exportExcel'])->name('exportExcel');
        Route::get('/report/export-by-date', [ResponseController::class, 'exportByDate'])->name('exportByDate');
        Route::get('/report/{id}', [ResponseController::class, 'response'])->name('response');
        Route::post('/report/show/{id}', [ResponseController::class, 'store'])->name('store');
        Route::get('/report/progres/{id}', [ResponseController::class, 'progres'])->name('progres');
        Route::post('/report/progres/{id}', [ResponseController::class, 'progresStore'])->name('progres.store');
        Route::post('/report/reject/{id}', [ResponseController::class, 'reject'])->name('reject');
        Route::delete('/response/{id}/content/{index}', [ResponseController::class, 'destroyContent'])->name('content.destroy');
    });
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard')->middleware('HeadStaff');


    Route::prefix('user')->middleware('HeadStaff')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/create/post', [UserController::class, 'store'])->name('store');
        Route::put('/reset{id}', [UserController::class, 'reset'])->name('reset');
        Route::delete('/destroy{id}', [UserController::class, 'destroy'])->name('destroy');
    });


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
