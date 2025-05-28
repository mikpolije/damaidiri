<?php

use App\Http\Controllers\API\AuthController as API_AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\JournalController;
use App\Http\Controllers\API\PartnerController;
use App\Http\Controllers\API\ProfileController as API_ProfileController;
use App\Http\Controllers\API\QuestionnaireController;
use App\Http\Controllers\API\RegencyController;
use App\Http\Controllers\API\ScreeningController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [API_AuthController::class, 'login']);
Route::post('/login-with-google', [API_AuthController::class, 'login_with_google']);
Route::post('/register', [API_AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [API_AuthController::class, 'logout']);

    Route::get('/my-profile', [API_AuthController::class, 'my_profile']);
    Route::get('/my-overview', [API_AuthController::class, 'my_overview']);
    Route::put('/update-account', [API_ProfileController::class, 'update_account']);
    Route::post('/store-profile', [API_ProfileController::class, 'store_profile']);
    Route::put('/update-profile', [API_ProfileController::class, 'update_profile']);
    Route::put('/update-password', [API_ProfileController::class, 'update_password']);
    Route::get('/regencies', [RegencyController::class, 'list']);

    Route::controller(BlogController::class)->group(function () {
        Route::prefix('blog')->group(function () {
            Route::get('/categories', 'categories');
            Route::get('/', 'blog');
            Route::get('/{id}', 'detail');
        });
    });
    Route::controller(QuestionnaireController::class)->group(function () {
        Route::prefix('questionnaire')->group(function () {
            Route::get('/', 'list');
            Route::get('/categories/{id}', 'listCategories');
            Route::get('/questions', 'listQuestions');
            Route::get('/question-answers/{questionnaire_id}', 'listQuestionAnswers');
        });
    });
    Route::controller(ScreeningController::class)->group(function () {
        Route::prefix('screening')->group(function () {
            Route::post('{id}', 'store');
            Route::get('', 'listHistory');
            Route::get('{id}', 'detailHistory');
            Route::get('/availability/check', 'availabilityCheck');
        });
    });
    Route::controller(JournalController::class)->group(function () {
        Route::prefix('journal')->group(function () {
            Route::get('', 'list');
            Route::get('{id}/question', 'listQuestion');
            Route::post('{id}', 'store');
            Route::get('response', 'listResponse');
            Route::get('response/{id}', 'detailResponse');
        });
    });
    Route::controller(PartnerController::class)->group(function () {
        Route::prefix('partner')->group(function () {
            Route::get('', 'list');
            Route::get('{id}', 'detail');
        });
    });
});