<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Cloudinary\Configuration\Configuration;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Configuration::instance([
//     'cloud' => [
//         'cloud_name' => env('CLOUDINARY_NAME'),
//         'api_key' => env('CLOUDINARY_KEY'),
//         'api_secret' => env('CLOUDINARY_SECRET')
//     ],
//     'url' => [
//         'secure' => true
//     ]
// ]);


// Route::post('/register', [RegisterController::class, 'register']);
// Route::post('/login', [RegisterController::class, 'login']);

// // Route::middleware('auth:api')->get('/user', function (Request $request) {
// //     return $request->user();
// // });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:api')->group(function () {
//     Route::resource('products', ProductController::class);
//     Route::post('/logout', [RegisterController::class, 'logout']);
//     Route::get('/updateprofile',[RegisterController::class,'updateprofile']);
    
// });
// Route::get('/upload', [FileUploadController::class,'showUploadForm']);
// Route::post('/upload', [FileUploadController::class,'storeUploads']);