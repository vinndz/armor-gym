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
use App\Http\Controllers\GymScheduleController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ReportController;


Route::get("/", [DashboardController::class, "index"]);

Route::middleware(['guest'])->group(function(){
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login-action', [LoginController::class, 'loginAction'])->name('login.action');
});

Route::get('register', [LoginController::class, 'register'])->name('register');
Route::post('register-save', [LoginController::class, 'registerSave'])->name('register.save');

Route::get('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'user-access:ADMIN'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function(){
        Route::get('home', [HomeController::class, 'indexAdmin'])->name('home');
        Route::get('profile', [ProfileController::class, 'indexAdmin'])->name('profile');
        Route::put('update-profile/{id}', [ProfileController::class, 'updateProfile'])->name('update-profile');
        Route::put('upload-profile-picture/{id}', [ProfileController::class, 'uploadProfilePictureAdmin'])->name('upload-profile-picture');
    });
    // ----------============ Instructor Data ----------============ \\
    Route::prefix('instructor-data')->name('instructor-data.')->group(function(){
        Route::get('index', [InstructorDataController::class, 'index'])->name('index');
        Route::post('store', [InstructorDataController::class, 'store'])->name('store');
        Route::delete('destroy/{id}', [InstructorDataController::class, 'destroy'])->name('destroy');
        Route::get('data', [InstructorDataController::class, 'data'])->name('data');
    });

    // ----------============ Membership Data----------============ \\
    Route::prefix('membership-data')->name('membership-data.')->group(function(){
        Route::get('index', [MembershipController::class, 'index'])->name('index');
        Route::post('store', [MembershipController::class, 'store'])->name('store');
        Route::get('data', [MembershipController::class, 'data'])->name('data');
        Route::put('update/{id}', [MembershipController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [MembershipController::class, 'destroy'])->name('destroy');
    });

    // ----------============ Membership Transaction----------============ \\
    Route::prefix('membership-transaction')->name('membership-transaction.')->group(function(){
        Route::get('index', [MembershipTransactionController::class, 'index'])->name('index');
        Route::post('store', [MembershipTransactionController::class, 'store'])->name('store');
        Route::get('data', [MembershipTransactionController::class, 'data'])->name('data');
        Route::put('update/{id}', [MembershipTransactionController::class, 'update'])->name('update');
        Route::get('card-member/{id}', [MembershipTransactionController::class, 'cardMember'])->name('card-member');
        Route::get('receipt/{userId}', [MembershipTransactionController::class, 'receipt'])->name('receipt');
    });

    // ----------============ Daily Gym Transaction----------============ \\
    Route::prefix('daily-gym-transaction')->name('daily-gym-transaction.')->group(function(){
        Route::get('index', [DailyGymTransactionController::class, 'index'])->name('index');
        Route::get('detail/{userId}', [DailyGymTransactionController::class, 'detail'])->name('detail');
        Route::get('receipt/{userId}', [DailyGymTransactionController::class, 'receipt'])->name('receipt');
        Route::post('store', [DailyGymTransactionController::class, 'store'])->name('store');
        Route::put('update/{id}', [DailyGymTransactionController::class, 'update'])->name('update');
        Route::get('data', [DailyGymTransactionController::class, 'data'])->name('data');
    });


    // ----------============ Suplement Data----------============ \\
    Route::prefix('suplement-data')->name('suplement-data.')->group(function(){
        Route::get('index', [SuplementController::class, 'index'])->name('index');
        Route::get('data', [SuplementController::class, 'data'])->name('data');
        Route::post('store', [SuplementController::class, 'store'])->name('store');
        Route::put('update/{id}', [SuplementController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [SuplementController::class, 'destroy'])->name('destroy');
    });

    // ----------============ Suplement Transaction ----------============ \\
    Route::prefix('suplement-transaction')->name('suplement-transaction.')->group(function(){
        Route::get('index', [SuplementTransactionController::class, 'index'])->name('index');
        Route::get('data', [SuplementTransactionController::class, 'data'])->name('data');
        Route::get('detail/{userId}', [SuplementTransactionController::class, 'detail'])->name('detail');
        Route::get('receipt/{userId}', [SuplementTransactionController::class, 'receipt'])->name('receipt');
        Route::post('store', [SuplementTransactionController::class, 'store'])->name('store');
        Route::put('update/{id}', [SuplementTransactionController::class, 'update'])->name('update');
    });  
});

Route::middleware(['auth', 'user-access:INSTRUCTOR'])->group(function () {
    Route::prefix('instructor')->name('instructor.')->group(function(){
        Route::get('home', [HomeController::class, 'indexInstructor'])->name('home');
        Route::get('profile', [ProfileController::class, 'indexInstructor'])->name('profile');
        Route::put('update-profile/{id}', [ProfileController::class, 'updateProfile'])->name('update-profile');
        Route::put('upload-profile-picture/{id}', [ProfileController::class, 'uploadProfilePictureInstructor'])->name('upload-profile-picture');
    });
         // ----------============ Program Data ----------============ \\
    Route::prefix('program-data')->name('program-data.')->group(function(){
        Route::get('index', [ProgramDataController::class, 'index'])->name('index');
        Route::post('store', [ProgramDataController::class, 'store'])->name('store');
        Route::put('update/{id}', [ProgramDataController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [ProgramDataController::class, 'destroy'])->name('destroy');
        Route::get('data', [ProgramDataController::class, 'data'])->name('data');
    });

     // ----------============ Program Member ----------============ \\
    Route::prefix('program-member')->name('program-member.')->group(function(){
        Route::get('index', [ProgramMemberController::class, 'index'])->name('index');
        Route::post('store', [ProgramMemberController::class, 'store'])->name('store');
        Route::put('update/{id}', [ProgramMemberController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [ProgramMemberController::class, 'destroy'])->name('destroy');
        Route::get('data', [ProgramMemberController::class, 'data'])->name('data');
    });

      // ----------============ Gym Schedule ----------============ \\
      Route::prefix('gym-schedule')->name('gym-schedule.')->group(function(){
        Route::get('index', [GymScheduleController::class, 'index'])->name('index');
        Route::get('data', [GymScheduleController::class, 'data'])->name('data');
        Route::post('store', [GymScheduleController::class, 'store'])->name('store');
        Route::put('update/{id}', [GymScheduleController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [GymScheduleController::class, 'destroy'])->name('destroy');
        Route::put('update-status/{id}', [GymScheduleController::class, 'updateStatus'])->name('update-status');
        Route::get('index-member', [GymScheduleController::class, 'indexMember'])->name('index-member');
        Route::get('data-member', [GymScheduleController::class, 'dataMember'])->name('data-member');
    });


});

Route::middleware(['auth', 'user-access:MEMBER'])->group(function () {
    Route::prefix('member')->name('member.')->group(function(){
        Route::get('home', [HomeController::class, 'indexMember'])->name('home');
        Route::get('profile', [ProfileController::class, 'indexMember'])->name('profile');
        Route::put('update-profile/{id}', [ProfileController::class, 'updateProfile'])->name('update-profile');
        Route::put('upload-profile-picture/{id}', [ProfileController::class, 'uploadProfilePictureMember'])->name('upload-profile-picture');
    });
    Route::prefix('gym-schedule')->name('gym-schedule.')->group(function(){
        Route::get('index-member', [GymScheduleController::class, 'indexMember'])->name('index-member');
        Route::get('data-member', [GymScheduleController::class, 'dataMember'])->name('data-member');
       
    });
});

Route::middleware(['auth', 'user-access:OWNER'])->group(function () {
    Route::prefix('owner')->name('owner.')->group(function(){
        Route::get('home', [HomeController::class, 'indexOwner'])->name('home');
        Route::get('profile', [ProfileController::class, 'indexOwner'])->name('profile');  
        Route::put('update-profile/{id}', [ProfileController::class, 'updateProfile'])->name('update-profile');
        Route::put('upload-profile-picture/{id}', [ProfileController::class, 'uploadProfilePictureOwner'])->name('upload-profile-picture');
    });
    Route::prefix('report')->name('report.')->group(function(){
        Route::get('index', [ReportController::class, 'index'])->name('index');
        Route::get('index-monthly', [ReportController::class, 'indexMonthly'])->name('index-monthly');
        Route::get('monthly-report', [ReportController::class, 'monthlyReport'])->name('monthly-report');
        Route::get('daily-report', [ReportController::class, 'dailyReport'])->name('daily-report');
    });
});

Route::middleware(['auth', 'user-access:GUEST'])->group(function () {
    Route::prefix('guest')->name('guest.')->group(function(){
        Route::get('home', [HomeController::class, 'indexGuest'])->name('home');
        Route::get('profile', [ProfileController::class, 'indexGuest'])->name('profile');     
        Route::put('update-profile/{id}', [ProfileController::class, 'updateProfile'])->name('update-profile');
        Route::put('upload-profile-picture/{id}', [ProfileController::class, 'uploadProfilePictureGuest'])->name('upload-profile-picture');
    });
   
});
