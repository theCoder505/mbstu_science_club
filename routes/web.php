<?php

use App\Http\Controllers\AdminWebTasksController;
use App\Http\Controllers\AdvisorActionsController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Committee;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WebPagesController;
use Illuminate\Support\Facades\Route;


Route::get('/', [WebPagesController::class, 'homePage'])->name('home')->middleware('statistics');
Route::get('/teammate/{id}/{name}', [WebPagesController::class, 'teammateProfile'])->name('teammateProfile')->middleware('statistics');
Route::get('/about-us', [WebPagesController::class, 'aboutUs'])->name('aboutUs')->middleware('statistics');
Route::get('/privacy-policy', [WebPagesController::class, 'privacy'])->name('privacy')->middleware('statistics');
Route::get('/terms-and-conditions', [WebPagesController::class, 'terms_conditions'])->name('terms_conditions')->middleware('statistics');
Route::get('/code-of-conduct', [WebPagesController::class, 'code_of_conduct'])->name('code_of_conduct')->middleware('statistics');
Route::get('/application', [ApplicationController::class, 'application'])->name('application')->middleware('statistics');
Route::get('/track-certificate', [ApplicationController::class, 'track_application'])->name('track_page')->middleware('statistics');



Route::post('/apply-certificate', [ApplicationController::class, 'apply_certificate'])->name('apply_certificate');
Route::post('/track-certificate', [ApplicationController::class, 'track_result'])->name('track_result');
Route::post('/download-certificate', [ApplicationController::class, 'download_certificate'])->name('download_certificate');

Route::post('/email-otp', [WebPagesController::class, 'contactOTP'])->name('contactOTP');
Route::post('/verify-and-send-email', [WebPagesController::class, 'verifyAndSendEmail'])->name('verifyAndSendEmail');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [WebPagesController::class, 'adminDashboard'])->name('dashboard');


    
    Route::get('/applications', [ApplicationController::class, 'applications'])->name('applications');
    Route::get('/applications/{id}/{applicant_name}', [ApplicationController::class, 'validate_application'])->name('validate_application');
    Route::post('/application/update', [ApplicationController::class, 'updateApplication'])->name('update_application');
    Route::post('/application/delete', [ApplicationController::class, 'delete_application'])->name('delete_application');



    Route::get('/advisors-and-moderators', [AdvisorController::class, 'index'])->name('advisors.index');
    Route::get('/advisors-and-moderators/create', [AdvisorController::class, 'create'])->name('advisors.create');
    Route::post('/advisors-and-moderators', [AdvisorController::class, 'store'])->name('advisors.store');
    Route::get('/advisors-and-moderators/{id}/edit', [AdvisorController::class, 'edit'])->name('advisors.edit');
    Route::post('/advisors-and-moderators/update', [AdvisorController::class, 'update'])->name('advisors.update');
    Route::delete('/advisors-and-moderators/{id}', [AdvisorController::class, 'destroy'])->name('advisors.destroy');




    Route::get('/manage-team', [TeamController::class, 'index'])->name('team.index');
    Route::get('/manage-team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/manage-team/store', [TeamController::class, 'store'])->name('team.store');
    Route::get('/manage-team/{id}', [TeamController::class, 'edit'])->name('team.edit');
    Route::post('/manage-team/update', [TeamController::class, 'update'])->name('team.update');
    Route::post('/manage-team/destroy', [TeamController::class, 'destroy'])->name('team.destroy');



    Route::get('/website-settings', [AdminWebTasksController::class, 'websiteSettings'])->name('web_settings.index');
    Route::post('/website-settings/update', [AdminWebTasksController::class, 'updateWebSettings'])->name('web_settings.update');


    Route::get('/our-committee', [Committee::class, 'index'])->name('committees.index');
    Route::get('/our-committee/create', [Committee::class, 'create'])->name('committees.create');
    Route::post('/our-committee/store', [Committee::class, 'store'])->name('committees.store');
    Route::get('/our-committee/{id}', [Committee::class, 'edit'])->name('committees.edit');
    Route::post('/our-committee/update', [Committee::class, 'update'])->name('committees.update');
    Route::post('/our-committee/delete', [Committee::class, 'destroy'])->name('committees.destroy');




    Route::get('/faqs', [FAQController::class, 'index'])->name('faqs.index');
    Route::post('/faqs/store', [FAQController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/update', [FAQController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{id}', [FAQController::class, 'destroy'])->name('faqs.destroy');




    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery/store', [GalleryController::class, 'store'])->name('gallery.store');
    Route::post('/gallery/update', [GalleryController::class, 'update'])->name('gallery.update'); // Changed to POST to support file uploads
    Route::post('/gallery/update-positions', [GalleryController::class, 'updatePositions'])->name('gallery.updatePositions');
    Route::delete('/gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');





    
    
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/create', [TemplateController::class, 'create'])->name('templates.create');
    Route::post('/templates/store', [TemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{id}', [TemplateController::class, 'edit'])->name('templates.edit');
    Route::post('/templates/update', [TemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{id}', [TemplateController::class, 'destroy'])->name('templates.destroy');
});





// advisor portal 
Route::get('/advisor/login', [AdvisorActionsController::class, 'advisorLogin'])->name('advisor_panel.login_page');
Route::post('/advisor/login', [AdvisorActionsController::class, 'verifyAndLogin'])->name('advisor_panel.login');

// Advisor portal - Protected routes
Route::middleware(['advisor_auth'])->group(function () {
    Route::get('/advisor/dashboard', [AdvisorActionsController::class, 'advisorDashboard'])->name('advisor_panel.dahsboard');
    Route::post('/advisor/approve-application', [AdvisorActionsController::class, 'approveApplication'])->name('advisor_panel.approve');

    Route::get('/advisor/profile', [AdvisorActionsController::class, 'advisorProfile'])->name('advisor_panel.profile');
    Route::post('/advisor/update-profile', [AdvisorActionsController::class, 'updateProfile'])->name('advisor_panel.update_profile');
    
    Route::post('/advisor/logout', [AdvisorActionsController::class, 'logout'])->name('advisor_panel.logout');
});



require __DIR__ . '/settings.php';
