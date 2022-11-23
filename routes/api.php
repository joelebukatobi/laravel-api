<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/products', [ProductController::class, 'index']);
// Route::post('/products', [ProductController::class, 'store']);
// Route::resource('products', ProductController::class);

// Public Routes
// ------------------------------------------------------------------------
// Authentication
Route::post('/login', [AuthController::class, 'login']);
// Category 
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);
// Tags
Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{slug}', [TagController::class, 'show']);
// Posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
// Subscription
Route::post('/subscriptions', [SubscriptionController::class, 'store']);



// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // User
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/me', [UserController::class, 'show']);
    Route::post('/users/{username}', [UserController::class, 'update']);
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    // Categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{slug}', [CategoryController::class, 'update']);
    Route::delete('/categories/{slug}', [CategoryController::class, 'destroy']);
    // Tags
    Route::post('/tags', [TagController::class, 'store']);
    Route::put('/tags/{slug}', [TagController::class, 'update']);
    Route::delete('/tags/{slug}', [TagController::class, 'destroy']);
    // Posts
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/posts/{slug}', [PostController::class, 'update']);
    Route::delete('/posts/{slug}', [PostController::class, 'destroy']);
    // Subscription
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });