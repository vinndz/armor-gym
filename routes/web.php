<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstructorDataController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\SuplementController;
use App\Http\Controllers\DailyGymTransactionController;
use App\Http\Controllers\SuplementTransactionController;
use App\Http\Controllers\MembershipTransactionController;

use App\Http\Controllers\ProgramDataController;
use App\Http\Controllers\ProgramMemberController;


Route::get("/", [DashboardController::class, "index"]);

Route::middleware(['guest'])->group(function(){
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login-action', [LoginController::class, 'loginAction'])->name('login.action');
});

Route::get('register', [LoginController::class, 'register'])->name('register');
Route::post('register-save', [LoginController::class, 'registerSave'])->name('register.save');

Route::get('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'user-access:ADMIN'])->group(function () {
    Route::get('admin/home', [HomeController::class, 'indexAdmin'])->name('admin.home');

    // ----------============ Instructor Data ----------============ \\
    Route::prefix('instructor-data')->name('instructor-data.')->group(function(){
        Route::get('index', [InstructorDataController::class, 'index'])->name('index');
        Route::post('store', [InstructorDataController::class, 'store'])->name('store');
        Route::delete('destroy/{id}', [InstructorDataController::class, 'destroy'])->name('destroy');
        Route::get('search', [InstructorDataController::class, 'search'])->name('search');
    });

    // ----------============ Membership Data----------============ \\
    Route::prefix('membership-data')->name('membership-data.')->group(function(){
        Route::get('index', [MembershipController::class, 'index'])->name('index');
        Route::post('store', [MembershipController::class, 'store'])->name('store');
        Route::get('edit/{id}', [MembershipController::class, 'edit/{id}'])->name('edit/{id}');
        Route::put('update/{id}', [MembershipController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [MembershipController::class, 'destroy'])->name('destroy');
        Route::get('search', [MembershipController::class, 'search'])->name('search');
    });

    // ----------============ Membership Transaction----------============ \\
    Route::prefix('membership-transaction')->name('membership-transaction.')->group(function(){
        Route::get('index', [MembershipTransactionController::class, 'index'])->name('index');
        Route::post('store', [MembershipTransactionController::class, 'store'])->name('store');
        Route::get('search', [MembershipTransactionController::class, 'search'])->name('search');
    });

    // ----------============ Daily Gym Transaction----------============ \\
    Route::prefix('daily-gym-transaction')->name('daily-gym-transaction.')->group(function(){
        Route::get('index', [DailyGymTransactionController::class, 'index'])->name('index');
        Route::post('store', [DailyGymTransactionController::class, 'store'])->name('store');
        Route::put('update/{id}', [DailyGymTransactionController::class, 'update'])->name('update');
        Route::get('search', [DailyGymTransactionController::class, 'search'])->name('search');
    });


    // ----------============ Suplement Data----------============ \\
    Route::prefix('suplement-data')->name('suplement-data.')->group(function(){
        Route::get('index', [SuplementController::class, 'index'])->name('index');
        Route::post('store', [SuplementController::class, 'store'])->name('store');
        Route::put('update/{id}', [SuplementController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [SuplementController::class, 'destroy'])->name('destroy');
        Route::get('search', [SuplementController::class, 'search'])->name('search');
    });

    // ----------============ Suplement Transaction ----------============ \\
    Route::prefix('suplement-transaction')->name('suplement-transaction.')->group(function(){
        Route::get('index', [SuplementTransactionController::class, 'index'])->name('index');
        Route::post('store', [SuplementTransactionController::class, 'store'])->name('store');
        Route::get('search', [SuplementTransactionController::class, 'search'])->name('search');
    });  
});

Route::middleware(['auth', 'user-access:INSTRUCTOR'])->group(function () {
        Route::get('instructor/home', [HomeController::class, 'indexInstructor'])->name('instructor.home');

         // ----------============ Program Data ----------============ \\
    Route::prefix('program-data')->name('program-data.')->group(function(){
        Route::get('index', [ProgramDataController::class, 'index'])->name('index');
        Route::post('store', [ProgramDataController::class, 'store'])->name('store');
        Route::put('update/{id}', [ProgramDataController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [ProgramDataController::class, 'destroy'])->name('destroy');
        Route::get('search', [ProgramDataController::class, 'search'])->name('search');
    });

     // ----------============ Program Member ----------============ \\
    Route::prefix('program-member')->name('program-member.')->group(function(){
        Route::get('index', [ProgramMemberController::class, 'index'])->name('index');
        Route::post('store', [ProgramMemberController::class, 'store'])->name('store');
        Route::put('update/{id}', [ProgramMemberController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [ProgramMemberController::class, 'destroy'])->name('destroy');
        Route::get('search', [ProgramMemberController::class, 'search'])->name('search');
    });


});

Route::middleware(['auth', 'user-access:MEMBER'])->group(function () {
    Route::get('member/home', [HomeController::class, 'indexMember'])->name('member.home');
});

Route::middleware(['auth', 'user-access:OWNER'])->group(function () {
    Route::get('owner/home', [HomeController::class, 'indexOwner'])->name('owner.home');
});

Route::middleware(['auth', 'user-access:GUEST'])->group(function () {
    Route::get('guest/home', [HomeController::class, 'indexGuest'])->name('guest.home');
});
