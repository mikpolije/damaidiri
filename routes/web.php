<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionnaireDetailCategoryController;
use App\Http\Controllers\SimulationQuestionnaireController;
use App\Http\Controllers\QuestionnaireScreeningController;
use App\Http\Controllers\QuestionnaireCategoryController;
use App\Http\Controllers\QuestionnaireQuestionController;
use App\Http\Controllers\QuestionnaireHistoryController;
use App\Http\Controllers\QuestionnaireAnswerController;
use App\Http\Controllers\SimulationJournalController;
use App\Http\Controllers\DocumentationApiController;
use App\Http\Controllers\HistoryScreeningController;
use App\Http\Controllers\HistoryResponseController;
use App\Http\Controllers\JournalResponseController;
use App\Http\Controllers\JournalQuestionController;
use App\Http\Controllers\CheckScreeningController;
use App\Http\Controllers\JournalHistoryController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogGalleryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

// Route::get('/', function () { return view('maintenance'); })->name('maintenance');
Route::get('/', function () { return redirect()->route('redirect.auth'); })->name('home-landing');

Route::middleware(['auth'])->group(function () {
    // Route Redirect
    Route::get('/redirect', [RedirectController::class, 'redirect'])->name('redirect.auth');

    // Route Dashboard
    Route::group(['middleware' => 'auth'], function () { 
        Route::middleware('permission:dashboard_superadmin')->get('/dashboard-superadmin', [DashboardController::class, 'dashboard_superadmin'])->name('dashboard.superadmin');
        Route::middleware('permission:dashboard_admin')->get('/dashboard-admin', [DashboardController::class, 'dashboard_admin'])->name('dashboard.admin');
        Route::middleware('permission:dashboard_patient')->get('/dashboard-patient', [DashboardController::class, 'dashboard_patient'])->name('dashboard.patient');
        Route::middleware('permission:dashboard_psychologist')->get('/dashboard-psychologist', [DashboardController::class, 'dashboard_psychologist'])->name('dashboard.psychologist');
    });

    // Route Profile
    Route::prefix('/profile')->group(function (){
       Route::get('/my-account', [ProfileController::class, 'my_account'])->middleware('permission:my_account')->name('profile.my-account');
       Route::put('/my-account/update', [ProfileController::class, 'update_my_account'])->middleware('permission:my_account')->name('profile.my-account.update');
       Route::get('/my-profile', [ProfileController::class, 'my_profile'])->middleware('permission:my_profile')->name('profile.my-profile');
       Route::put('/my-profile/update', [ProfileController::class, 'update_my_profile'])->middleware('permission:my_profile')->name('profile.my-profile.update');
       Route::post('/my-profile/store', [ProfileController::class, 'store_my_profile'])->middleware('permission:my_profile')->name('profile.my-profile.store');
       Route::get('/my-profile-psychologist', [ProfileController::class, 'my_profile_psychologist'])->middleware('permission:my_profile_psychologist')->name('profile.my-profile-psychologist');
       Route::put('/my-profile-psychologist/update', [ProfileController::class, 'update_my_profile_psychologist'])->middleware('permission:my_profile_psychologist')->name('profile.my-profile-psychologist.update');
       Route::post('/my-profile-psychologist/store', [ProfileController::class, 'store_my_profile_psychologist'])->middleware('permission:my_profile_psychologist')->name('profile.my-profile-psychologist.store');
       Route::get('/my-password', [ProfileController::class, 'my_password'])->middleware('permission:my_password')->name('profile.my-password');
       Route::put('/my-password/update', [ProfileController::class, 'update_my_password'])->middleware('permission:my_password')->name('profile.my-password.update');

    });

    // Route Permission
    Route::prefix('/permission')->group(function (){
        Route::get('/list', [PermissionController::class, 'list'])->middleware('permission:list_permission')->name('permission.list');
        Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:create_permission')->name('permission.create');
        Route::post('/store', [PermissionController::class, 'store'])->middleware('permission:create_permission')->name('permission.store');
        Route::get('/{id}/detail', [PermissionController::class, 'detail'])->middleware('permission:read_permission')->name('permission.detail');
        Route::get('/{id}/edit', [PermissionController::class, 'edit'])->middleware('permission:update_permission')->name('permission.edit');
        Route::put('/{id}/update', [PermissionController::class, 'update'])->middleware('permission:update_permission')->name('permission.update');
        Route::delete('/{id}/destroy', [PermissionController::class, 'destroy'])->middleware('permission:delete_permission')->name('permission.destroy');
    });

    // Route Role
    Route::prefix('/role')->group(function (){
        Route::get('/list', [RoleController::class, 'list'])->middleware('permission:list_role')->name('role.list');
        Route::get('/{id}/detail', [RoleController::class, 'detail'])->middleware('permission:read_role')->name('role.detail');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->middleware('permission:update_role')->name('role.edit');
        Route::put('/{id}/update', [RoleController::class, 'update'])->middleware('permission:update_role')->name('role.update');
    });

    // Route User
    Route::prefix('/user')->group(function (){
        Route::get('/list', [UserController::class, 'list'])->middleware('permission:list_user')->name('user.list');
        Route::get('/create', [UserController::class, 'create'])->middleware('permission:create_user')->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->middleware('permission:create_user')->name('user.store');
        Route::get('/{id}/detail', [UserController::class, 'detail'])->middleware('permission:read_user')->name('user.detail');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->middleware('permission:update_user')->name('user.edit');
        Route::put('/{id}/update', [UserController::class, 'update'])->middleware('permission:update_user')->name('user.update');
        Route::delete('/{id}/destroy', [UserController::class, 'destroy'])->middleware('permission:delete_user')->name('user.destroy');
        
        Route::put('/{id}/activated', [UserController::class, 'activated_user'])->middleware('permission:activated_user')->name('user.activated');
        Route::put('/{id}/deactivated', [UserController::class, 'deactivated_user'])->middleware('permission:deactivated_user')->name('user.deactivated');
    });

    // Route Master Partner
    Route::prefix('/master-partner')->group(function () {
        Route::get('/list', [PartnerController::class, 'list'])->middleware('permission:list_partner')->name('master-partner.list');
        Route::get('/create', [PartnerController::class, 'create'])->middleware('permission:create_partner')->name('master-partner.create');
        Route::post('/store', [PartnerController::class, 'store'])->middleware('permission:create_partner')->name('master-partner.store');
        Route::get('/{id}/detail', [PartnerController::class, 'detail'])->middleware('permission:read_partner')->name('master-partner.detail');
        Route::get('/{id}/edit', [PartnerController::class, 'edit'])->middleware('permission:update_partner')->name('master-partner.edit');
        Route::put('/{id}/update', [PartnerController::class, 'update'])->middleware('permission:update_partner')->name('master-partner.update');
        Route::delete('/{id}/destroy', [PartnerController::class, 'destroy'])->middleware('permission:delete_partner')->name('master-partner.destroy');
    });

    // Route Blog Category
    Route::prefix('/blog-category')->group(function () {
        Route::get('/list', [BlogCategoryController::class, 'list'])->middleware('permission:list_blog_category')->name('blog-category.list');
        Route::get('/create', [BlogCategoryController::class, 'create'])->middleware('permission:create_blog_category')->name('blog-category.create');
        Route::post('/store', [BlogCategoryController::class, 'store'])->middleware('permission:create_blog_category')->name('blog-category.store');
        Route::get('/{id}/detail', [BlogCategoryController::class, 'detail'])->middleware('permission:read_blog_category')->name('blog-category.detail');
        Route::get('/{id}/edit', [BlogCategoryController::class, 'edit'])->middleware('permission:update_blog_category')->name('blog-category.edit');
        Route::put('/{id}/update', [BlogCategoryController::class, 'update'])->middleware('permission:update_blog_category')->name('blog-category.update');
        Route::delete('/{id}/destroy', [BlogCategoryController::class, 'destroy'])->middleware('permission:delete_blog_category')->name('blog-category.destroy');
    });

    // Route Blog
    Route::prefix('/blog')->group(function () {
        Route::get('/list', [BlogController::class, 'list'])->middleware('permission:list_blog')->name('blog.list');
        Route::get('/create', [BlogController::class, 'create'])->middleware('permission:create_blog')->name('blog.create');
        Route::post('/store', [BlogController::class, 'store'])->middleware('permission:create_blog')->name('blog.store');
        Route::get('/{id}/detail', [BlogController::class, 'detail'])->middleware('permission:read_blog')->name('blog.detail');
        Route::get('/{id}/edit', [BlogController::class, 'edit'])->middleware('permission:update_blog')->name('blog.edit');
        Route::put('/{id}/update', [BlogController::class, 'update'])->middleware('permission:update_blog')->name('blog.update');
        Route::delete('/{id}/destroy', [BlogController::class, 'destroy'])->middleware('permission:delete_blog')->name('blog.destroy');

        Route::get('/{id}/blog-gallery', [BlogGalleryController::class, 'list'])->middleware('permission:list_blog_gallery')->name('blog.blog-gallery.list');
        Route::post('/{id}/blog-gallery/store', [BlogGalleryController::class, 'store'])->middleware('permission:create_blog_gallery')->name('blog.blog-gallery.store');
        Route::get('/{id}/blog-gallery/{gallery_id}/edit', [BlogGalleryController::class, 'edit'])->middleware('permission:update_blog_gallery')->name('blog.blog-gallery.edit');
        Route::put('/{id}/blog-gallery/{gallery_id}/update', [BlogGalleryController::class, 'update'])->middleware('permission:update_blog_gallery')->name('blog.blog-gallery.update');
        Route::delete('/{id}/blog-gallery/{gallery_id}/destroy', [BlogGalleryController::class, 'destroy'])->middleware('permission:delete_blog_gallery')->name('blog.blog-gallery.destroy');
    });

    // Route Master Questionnaire
    Route::prefix('/master-questionnaire')->group(function () {
        Route::get('/list', [QuestionnaireController::class, 'list'])->middleware('permission:list_questionnaire')->name('master-questionnaire.list');
        Route::get('/create', [QuestionnaireController::class, 'create'])->middleware('permission:create_questionnaire')->name('master-questionnaire.create');
        Route::post('/store', [QuestionnaireController::class, 'store'])->middleware('permission:create_questionnaire')->name('master-questionnaire.store');
        Route::get('/{id}/detail', [QuestionnaireController::class, 'detail'])->middleware('permission:read_questionnaire')->name('master-questionnaire.detail');
        Route::get('/{id}/edit', [QuestionnaireController::class, 'edit'])->middleware('permission:update_questionnaire')->name('master-questionnaire.edit');
        Route::put('/{id}/update', [QuestionnaireController::class, 'update'])->middleware('permission:update_questionnaire')->name('master-questionnaire.update');
        Route::delete('/{id}/destroy', [QuestionnaireController::class, 'destroy'])->middleware('permission:delete_questionnaire')->name('master-questionnaire.destroy');
        
        Route::get('/{id}/config-questionnaire', [QuestionnaireController::class, 'config'])->middleware('permission:config_questionnaire')->name('master-questionnaire.config');

        Route::get('/{id}/questionnaire-category/list', [QuestionnaireCategoryController::class, 'list'])->middleware('permission:list_questionnaire_category')->name('master-questionnaire.questionnaire-category.list');
        Route::post('/{id}/questionnaire-category/store', [QuestionnaireCategoryController::class, 'store'])->middleware('permission:create_questionnaire_category')->name('master-questionnaire.questionnaire-category.store');
        Route::get('/{id}/questionnaire-category/{category_id}/edit', [QuestionnaireCategoryController::class, 'edit'])->middleware('permission:update_questionnaire_category')->name('master-questionnaire.questionnaire-category.edit');
        Route::put('/{id}/questionnaire-category/{category_id}/update', [QuestionnaireCategoryController::class, 'update'])->middleware('permission:update_questionnaire_category')->name('master-questionnaire.questionnaire-category.update');
        Route::delete('/{id}/questionnaire-category/{category_id}/destroy', [QuestionnaireCategoryController::class, 'destroy'])->middleware('permission:delete_questionnaire_category')->name('master-questionnaire.questionnaire-category.destroy');

        Route::get('/{id}/questionnaire-detail-category/list', [QuestionnaireDetailCategoryController::class, 'list'])->middleware('permission:list_questionnaire_detail_category')->name('master-questionnaire.questionnaire-detail-category.list');
        Route::post('/{id}/questionnaire-detail-category/store', [QuestionnaireDetailCategoryController::class, 'store'])->middleware('permission:create_questionnaire_detail_category')->name('master-questionnaire.questionnaire-detail-category.store');
        Route::get('/{id}/questionnaire-detail-category/{detail_category_id}/edit', [QuestionnaireDetailCategoryController::class, 'edit'])->middleware('permission:update_questionnaire_detail_category')->name('master-questionnaire.questionnaire-detail-category.edit');
        Route::put('/{id}/questionnaire-detail-category/{detail_category_id}/update', [QuestionnaireDetailCategoryController::class, 'update'])->middleware('permission:update_questionnaire_detail_category')->name('master-questionnaire.questionnaire-detail-category.update');
        Route::delete('/{id}/questionnaire-detail-category/{detail_category_id}/destroy', [QuestionnaireDetailCategoryController::class, 'destroy'])->middleware('permission:delete_questionnaire_detail_category')->name('master-questionnaire.questionnaire-detail-category.destroy');

        Route::get('/{id}/questionnaire-question/list', [QuestionnaireQuestionController::class, 'list'])->middleware('permission:list_questionnaire_question')->name('master-questionnaire.questionnaire-question.list');
        Route::post('/{id}/questionnaire-question/store', [QuestionnaireQuestionController::class, 'store'])->middleware('permission:create_questionnaire_question')->name('master-questionnaire.questionnaire-question.store');
        Route::get('/{id}/questionnaire-question/{question_id}/edit', [QuestionnaireQuestionController::class, 'edit'])->middleware('permission:update_questionnaire_question')->name('master-questionnaire.questionnaire-question.edit');
        Route::put('/{id}/questionnaire-question/{question_id}/update', [QuestionnaireQuestionController::class, 'update'])->middleware('permission:update_questionnaire_question')->name('master-questionnaire.questionnaire-question.update');
        Route::delete('/{id}/questionnaire-question/{question_id}/destroy', [QuestionnaireQuestionController::class, 'destroy'])->middleware('permission:delete_questionnaire_question')->name('master-questionnaire.questionnaire-question.destroy');

        Route::get('/{id}/questionnaire-answer/list', [QuestionnaireAnswerController::class, 'list'])->middleware('permission:list_questionnaire_answer')->name('master-questionnaire.questionnaire-answer.list');
        Route::post('/{id}/questionnaire-answer/store', [QuestionnaireAnswerController::class, 'store'])->middleware('permission:create_questionnaire_answer')->name('master-questionnaire.questionnaire-answer.store');
        Route::get('/{id}/questionnaire-answer/{answer_id}/edit', [QuestionnaireAnswerController::class, 'edit'])->middleware('permission:update_questionnaire_answer')->name('master-questionnaire.questionnaire-answer.edit');
        Route::put('/{id}/questionnaire-answer/{answer_id}/update', [QuestionnaireAnswerController::class, 'update'])->middleware('permission:update_questionnaire_answer')->name('master-questionnaire.questionnaire-answer.update');
        Route::delete('/{id}/questionnaire-answer/{answer_id}/destroy', [QuestionnaireAnswerController::class, 'destroy'])->middleware('permission:delete_questionnaire_answer')->name('master-questionnaire.questionnaire-answer.destroy');

        Route::get('/{id}/questionnaire-history/list', [QuestionnaireHistoryController::class, 'list'])->middleware('permission:list_questionnaire_history')->name('master-questionnaire.questionnaire-history.list');
        Route::get('/{id}/questionnaire-history/{screening_id}/detail', [QuestionnaireHistoryController::class, 'detail'])->middleware('permission:detail_questionnaire_history')->name('master-questionnaire.questionnaire-history.detail');
    });

    // Route Simulation Questionnaire
    Route::prefix('/simulation-questionnaire')->group(function () {
        Route::get('/list', [SimulationQuestionnaireController::class, 'list'])->middleware('permission:list_simulation_questionnaire')->name('simulation-questionnaire.list');
        Route::get('/{questionnaire}/simulate', [SimulationQuestionnaireController::class, 'simulate'])->middleware('permission:simulate_simulation_questionnaire')->name('simulation-questionnaire.simulate');
        Route::post('/{questionnaire}/simulate/store', [SimulationQuestionnaireController::class, 'store'])->middleware('permission:simulate_simulation_questionnaire')->name('simulation-questionnaire.store');
    });

    // Route Questionnaire Screening
    Route::prefix('/questionnaire-screening')->group(function () {
        Route::get('/list', [QuestionnaireScreeningController::class, 'list'])->middleware(['permission:list_questionnaire_screening', 'patient.complete'])->name('questionnaire-screening.list');
        Route::get('/{questionnaire}/screening', [QuestionnaireScreeningController::class, 'screening'])->middleware(['permission:screening_questionnaire', 'patient.complete'])->name('questionnaire-screening.screening');
        Route::post('/{questionnaire}/screening/store', [QuestionnaireScreeningController::class, 'store'])->middleware(['permission:screening_questionnaire', 'patient.complete'])->name('questionnaire-screening.store');
    });

    // Route History Screening
    Route::prefix('/history-screening')->group(function () {
        Route::get('/list', [HistoryScreeningController::class, 'list'])->middleware(['permission:list_history_screening', 'patient.complete'])->name('history-screening.list');
        Route::get('{questionnaire}/{screening}/detail', [HistoryScreeningController::class, 'detail'])->middleware(['permission:detail_history_screening', 'patient.complete'])->name('history-screening.detail');
    });

    // Route Check Screening
    Route::prefix('/check-screening')->group(function () {
        Route::get('/form', [CheckScreeningController::class, 'form'])->middleware(['permission:check_screening', 'psychologist.complete'])->name('check-screening.form');
        Route::post('/result', [CheckScreeningController::class, 'result'])->middleware(['permission:check_screening', 'psychologist.complete'])->name('check-screening.result');
    });

    // Route Master Journal
    Route::prefix('/master-journal')->group(function () {
        Route::get('/list', [JournalController::class, 'list'])->middleware('permission:list_journal')->name('master-journal.list');
        Route::get('/create', [JournalController::class, 'create'])->middleware('permission:create_journal')->name('master-journal.create');
        Route::post('/store', [JournalController::class, 'store'])->middleware('permission:create_journal')->name('master-journal.store');
        Route::get('/{id}/detail', [JournalController::class, 'detail'])->middleware('permission:read_journal')->name('master-journal.detail');
        Route::get('/{id}/edit', [JournalController::class, 'edit'])->middleware('permission:update_journal')->name('master-journal.edit');
        Route::put('/{id}/update', [JournalController::class, 'update'])->middleware('permission:update_journal')->name('master-journal.update');
        Route::delete('/{id}/destroy', [JournalController::class, 'destroy'])->middleware('permission:delete_journal')->name('master-journal.destroy');

        Route::get('/{id}/config-journal', [JournalController::class, 'config'])->middleware('permission:config_journal')->name('master-journal.config');

        Route::get('/{id}/journal-question/list', [JournalQuestionController::class, 'list'])->middleware('permission:list_journal_question')->name('master-journal.journal-question.list');
        Route::post('/{id}/journal-question/store', [JournalQuestionController::class, 'store'])->middleware('permission:create_journal_question')->name('master-journal.journal-question.store');
        Route::get('/{id}/journal-question/{question_id}/edit', [JournalQuestionController::class, 'edit'])->middleware('permission:update_journal_question')->name('master-journal.journal-question.edit');
        Route::put('/{id}/journal-question/{question_id}/update', [JournalQuestionController::class, 'update'])->middleware('permission:update_journal_question')->name('master-journal.journal-question.update');
        Route::delete('/{id}/journal-question/{question_id}/destroy', [JournalQuestionController::class, 'destroy'])->middleware('permission:delete_journal_question')->name('master-journal.journal-question.destroy');

        Route::get('/{id}/journal-history/list', [JournalHistoryController::class, 'list'])->middleware('permission:list_journal_history')->name('master-journal.journal-history.list');
        Route::get('/{id}/journal-history/{response_id}/detail', [JournalHistoryController::class, 'detail'])->middleware('permission:detail_journal_history')->name('master-journal.journal-history.detail');
    });

    // Route Simulation Journal
    Route::prefix('/simulation-journal')->group(function () {
        Route::get('/list', [SimulationJournalController::class, 'list'])->middleware('permission:list_simulation_journal')->name('simulation-journal.list');
        Route::get('/{journal}/simulate', [SimulationJournalController::class, 'simulate'])->middleware('permission:simulate_simulation_journal')->name('simulation-journal.simulate');
        Route::post('/{journal}/simulate/store', [SimulationJournalController::class, 'store'])->middleware('permission:simulate_simulation_journal')->name('simulation-journal.store');
    });

    // Route Journal Response
    Route::prefix('/journal-response')->group(function () {
        Route::get('/list', [JournalResponseController::class, 'list'])->middleware(['permission:list_journal_response', 'patient.complete'])->name('journal-response.list');
        Route::get('/{journal}/response', [JournalResponseController::class, 'response'])->middleware(['permission:list_journal_response', 'patient.complete'])->name('journal-response.response');
        Route::post('/{journal}/response/store', [JournalResponseController::class, 'store'])->middleware(['permission:response_journal', 'patient.complete'])->name('journal-response.store');
    });

    // Route History Response
    Route::prefix('/history-response')->group(function () {
        Route::get('/list', [HistoryResponseController::class, 'list'])->middleware(['permission:list_history_response', 'patient.complete'])->name('history-response.list');
        Route::get('/{journal}/{response}/detail', [HistoryResponseController::class, 'detail'])->middleware(['permission:detail_history_response', 'patient.complete'])->name('history-response.detail');
    });

    // Route Documentation API
    Route::prefix('/documentation-api')->group(function () {
        Route::get('/list', [DocumentationApiController::class, 'list'])->middleware('permission:list_documentation_api')->name('documentation-api.list');
    });
});

require __DIR__.'/auth.php';
