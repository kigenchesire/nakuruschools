<?php

use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\ContactInformationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\GalleryAlbumController;
use App\Http\Controllers\Admin\GalleryImageController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin panel
|--------------------------------------------------------------------------
| Loaded from bootstrap/app.php with the "/admin" prefix, "admin." name
| prefix and the web + auth + active middleware.
*/

Route::redirect('/', '/admin/dashboard');
Route::get('dashboard', DashboardController::class)->name('dashboard');

// Content
Route::post('sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');
Route::patch('sliders/{slider}/toggle', [SliderController::class, 'toggle'])->name('sliders.toggle');
Route::resource('sliders', SliderController::class)->except('show');

Route::resource('about', AboutSectionController::class)
    ->parameters(['about' => 'section'])
    ->except('show');

Route::post('features/reorder', [FeatureController::class, 'reorder'])->name('features.reorder');
Route::patch('features/{feature}/toggle', [FeatureController::class, 'toggle'])->name('features.toggle');
Route::resource('features', FeatureController::class)->except('show');

Route::patch('news/{news}/publish', [NewsController::class, 'togglePublish'])->name('news.publish');
Route::patch('news/{news}/feature', [NewsController::class, 'toggleFeatured'])->name('news.feature');
Route::resource('news', NewsController::class)->parameters(['news' => 'news']);

Route::post('gallery/albums/reorder', [GalleryAlbumController::class, 'reorder'])->name('gallery.albums.reorder');
Route::resource('gallery/albums', GalleryAlbumController::class)
    ->names('gallery.albums')
    ->parameters(['albums' => 'album'])
    ->except('show');
Route::patch('gallery/{image}/toggle', [GalleryImageController::class, 'toggle'])->name('gallery.toggle');
Route::resource('gallery', GalleryImageController::class)
    ->parameters(['gallery' => 'image'])
    ->except('show');

Route::post('faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');
Route::patch('faqs/{faq}/toggle', [FaqController::class, 'toggle'])->name('faqs.toggle');
Route::resource('faqs', FaqController::class)->except('show');

Route::get('resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');
Route::patch('resources/{resource}/toggle', [ResourceController::class, 'toggle'])->name('resources.toggle');
Route::resource('resources', ResourceController::class)->except('show');

// Website
Route::get('contact', [ContactInformationController::class, 'edit'])->name('contact.edit');
Route::put('contact', [ContactInformationController::class, 'update'])->name('contact.update');

Route::get('social', [SocialLinkController::class, 'edit'])->name('social.edit');
Route::put('social', [SocialLinkController::class, 'update'])->name('social.update');

// Messages
Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
Route::get('enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
Route::patch('enquiries/{enquiry}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.status');
Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

// System
Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
Route::resource('users', UserController::class)->except('show');

Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');
