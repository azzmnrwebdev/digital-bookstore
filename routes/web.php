<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EbookTrashController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\RatingController as AdminRatingController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController as ControllersCategoryController;
use App\Http\Controllers\EbookController as ControllersEbookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Penulis\EbookController;
use App\Http\Controllers\Penulis\DashboardController as PenulisDashboardController;
use App\Http\Controllers\Penulis\NotificationController as PenulisNotificationController;
use App\Http\Controllers\Penulis\SalesReportController;
use App\Http\Controllers\Penulis\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TestimonialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware(['is_active'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('ebook', [ControllersEbookController::class, 'index'])->name('ebooks');
    Route::get('ebook/ebook-{slug}', [ControllersEbookController::class, 'show'])->name('ebook.detail');
    Route::post('ebook/download-free', [ControllersEbookController::class, 'downloadFree'])->name('ebook.download_free');
    Route::get('category', [ControllersCategoryController::class, 'index'])->name('categories');
    Route::get('category/category-{slug}/ebook', [ControllersCategoryController::class, 'getEbookByCategory'])->name('category.get_ebook');
    Route::get('catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('author/author-{username}', [ProfileController::class, 'author'])->name('author.profile');

    Route::middleware(['auth'])->group(function () {
        // all role
        Route::get('notification', [NotificationController::class, 'index'])->name('notification.index');
        Route::get('notification/read/{id}', [NotificationController::class, 'show'])->name('notification.show');
        Route::get('notification/unread', [NotificationController::class, 'unread'])->name('notification.unread');
        Route::delete('notification/{id}', [NotificationController::class, 'delete'])->name('notification.delete');

        Route::get('my-cart/count', [CartController::class, 'cartCount'])->name('cart.count');
        Route::post('my-cart/store', [CartController::class, 'store'])->name('cart.store');
        Route::get('my-cart', [CartController::class, 'index'])->name('cart.index');
        Route::delete('my-cart/{id}', [CartController::class, 'destroy'])->name('cart.delete');

        Route::get('checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('process-checkout', [OrderController::class, 'store'])->name('order.store');
        Route::get('my-order', [OrderController::class, 'index'])->name('order.index');
        Route::get('my-order/process', [OrderController::class, 'process'])->name('order.process');
        Route::get('my-order/approved', [OrderController::class, 'approved'])->name('order.approved');
        Route::put('my-order/approved/update-review/{id}', [RatingController::class, 'updateReview'])->name('order.approved_update_review');
        Route::post('my-order/approved/insert-review', [RatingController::class, 'storeReview'])->name('order.approved_insert_review');
        Route::get('my-order/rejected', [OrderController::class, 'rejected'])->name('order.rejected');

        Route::get('testimonial', [TestimonialController::class, 'index'])->name('testimonial.index');
        Route::post('testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');

        // role pembaca
        Route::prefix('reader')->middleware(['role:pembaca'])->group(function () {
            Route::get('profile', [ProfileController::class, 'profilPembaca'])->name('pembaca.profile');
            Route::get('profile/{username}/edit', [ProfileController::class, 'editProfile'])->name('pembaca.profile_edit');
            Route::put('profile/update/{id}', [ProfileController::class, 'updateProfile'])->name('pembaca.profile_update');
            Route::get('profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('pembaca.profile_change_password');
            Route::post('profile/change-password', [ProfileController::class, 'updatePassword'])->name('pembaca.profile_update_password');
        });

        // role penulis
        Route::prefix('penulis')->middleware(['role:penulis'])->group(function () {
            Route::get('/profile', [PenulisDashboardController::class, 'profile'])->name('penulis.profile');
            Route::get('/dashboard', [PenulisDashboardController::class, 'index'])->name('penulis.dashboard');
            Route::controller(PenulisNotificationController::class)->group(function () {
                Route::get('/notification', 'index')->name('penulis.notification.index');
                Route::get('/notification/read/{id}', 'show')->name('penulis.notification.show');
                Route::get('/notification/unread', 'unread')->name('penulis.notification.unread');
                Route::delete('/notification/{id}', 'destroy')->name('penulis.notification.destroy');
            });

            Route::controller(EbookController::class)->group(function () {
                Route::get('/ebook', 'index')->name('penulis.ebook.index');
                Route::get('/kontribusi-ebook', 'kontribusi')->name('penulis.ebook.kontribusi');
                Route::get('/ebook/upload', 'create')->name('penulis.ebook.create');
                Route::post('/ebook', 'store')->name('penulis.ebook.store');
                Route::get('/ebook/{slug}', 'show')->name('penulis.ebook.show');
                Route::get('/ebook/{slug}/edit', 'edit')->name('penulis.ebook.edit');
                Route::put('/ebook/{id}', 'update')->name('penulis.ebook.update');
                Route::delete('/ebook/{id}', 'destroy')->name('penulis.ebook.delete');
            });

            Route::controller(SettingController::class)->group(function () {
                Route::get('/setting/change-password', 'showChangePasswordForm')->name('penulis.change_password');
                Route::post('/setting/change-password', 'updatePassword')->name('penulis.update_password');
                Route::get('/setting/update-profile', 'showUpdateProfileForm')->name('penulis.show_update');
                Route::put('/setting/update-profile/{id}', 'updateProfile')->name('penulis.update_profile');
            });

            Route::controller(SalesReportController::class)->group(function () {
                Route::get('/sales-report', 'index')->name('penulis.sales_report');
            });
        });

        // role admin
        Route::prefix('admin')->middleware(['role:admin'])->group(function () {
            Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
            Route::controller(AdminNotificationController::class)->group(function () {
                Route::get('/notification', 'index')->name('admin.notification.index');
                Route::get('/notification/read/{id}', 'show')->name('admin.notification.show');
                Route::get('/notification/unread', 'unread')->name('admin.notification.unread');
                Route::delete('/notification/{id}', 'destroy')->name('admin.notification.destroy');
            });

            Route::controller(AdminOrderController::class)->group(function () {
                Route::get('/manageorder', 'index')->name('admin.manageorder.index');
                Route::get('/manageorder/{IDPesanan}/edit', 'edit')->name('admin.manageorder.edit');
                Route::put('/manageorder/{id}', 'update')->name('admin.manageorder.update');
                Route::delete('/manageorder/{id}', 'destroy')->name('admin.manageorder.destroy');
            });

            Route::controller(AdminSettingController::class)->group(function () {
                Route::get('/setting/change-password', 'showChangePasswordForm')->name('admin.change_password');
                Route::post('/setting/change-password', 'updatePassword')->name('admin.update_password');
                Route::get('/setting/update-profile', 'showUpdateProfileForm')->name('admin.show_update');
                Route::put('/setting/update-profile/{id}', 'updateProfile')->name('admin.update_profile');
            });

            // route resource
            Route::resource('/manageuser', UserController::class);
            Route::resource('/managecategory', AdminCategoryController::class);
            Route::resource('/managerating', AdminRatingController::class)->except('create', 'store', 'show', 'edit', 'update');
            Route::resource('/managetestimonial', AdminTestimonialController::class)->except('create', 'store', 'show', 'edit', 'update');
            Route::resource('/manageebooktrash', EbookTrashController::class)->except('create', 'store', 'edit', 'update', 'destroy');
            Route::put('/manageebooktrash/{manageebooktrash}', [EbookTrashController::class, 'restore'])->name('manageebooktrash.restore')->withTrashed();
            Route::delete('/manageebooktrash/{manageebooktrash}', [EbookTrashController::class, 'forceDelete'])->name('manageebooktrash.force_delete')->withTrashed();

            // route custom
            Route::get('datapengguna-pdf', [UserController::class, 'manageuserPDF'])->name('manageuser.pdf');
        });
    });
});

Route::get('verification-info', function () {
    return view('layouts.after-register');
})->name('verification_info')->middleware('new_user_verified');

Route::get('check-login-status', [HomeController::class, 'checkLoginStatus'])->name('check.login.status');
