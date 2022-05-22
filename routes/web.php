<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::post('/login', [IndexController::class, 'login'])->name('login')->middleware('throttle:login');

//admin保护
Route::middleware('auth:admin')->group(function () {
    //首页
    Route::get('/console', [IndexController::class, 'console'])->name('console');
    Route::get('/logout', [IndexController::class, 'logout'])->name('logout');

//    图书管理
    Route::prefix('book')->as('book.')->group(function () {
        Route::get('index', [BookController::class, 'index'])->name('index');
        Route::post('list', [BookController::class, 'list'])->name('list');
        Route::post('create', [BookController::class, 'create'])->name('create');
        Route::post('update', [BookController::class, 'update'])->name('update');
        Route::get('delete', [BookController::class, 'delete'])->name('delete');
    });

//    会员管理
    Route::prefix('member')->as('member.')->group(function () {
//        登录限流器
        Route::middleware('throttle:login')->group(function () {
            Route::post('loginByCard', [MemberController::class, 'loginByCard'])->name('loginByCard');
            Route::post('loginByPassword', [MemberController::class, 'loginByPassword'])->name('loginByPassword');
        });
        Route::get('index/{opt}', [MemberController::class, 'index'])->name('index');
        Route::post('register', [MemberController::class, 'register'])->name('register');
        Route::get('logout', [MemberController::class, 'logout'])->name('logout');
//        挂失使用密码登录，然后重新发卡
        Route::middleware('auth:memberPassword')->group(function () {
            Route::get('loss', [MemberController::class, 'loss'])->name('loss');
        });
//        正常业务使用刷卡登录
        Route::middleware('auth:memberCard')->group(function () {
            Route::get('borrowList', [MemberController::class, 'borrowList'])->name('borrowList');
            Route::post('recharge', [MemberController::class, 'recharge'])->name('recharge');
            Route::post('borrow', [MemberController::class, 'borrow'])->name('borrow');
            Route::get('showCost', [MemberController::class, 'showCost'])->name('showCost');
            Route::post('return', [MemberController::class, 'return'])->name('return');
            Route::get('delay', [MemberController::class, 'delay'])->name('delay');
            Route::post('edit', [MemberController::class, 'edit'])->name('edit');
        });
    });

//    收款管理
    Route::prefix('income')->as('income.')->group(function () {
        Route::get('index', [IncomeController::class, 'index'])->name('index');
        Route::post('list', [IncomeController::class, 'list'])->name('list');
        Route::get('penaltiesList', [IncomeController::class, 'penaltiesList'])->name('penaltiesList');
    });
});

