<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MyRecipeController;
use App\Http\Controllers\CommentController;
use App\Http\Resources\UserResource;


Route::middleware('jwt.auth')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});

Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::delete('/logout', [AuthController::class, 'logout'])
        ->middleware('jwt.auth')
        ->name('logout');

Route::resource('/recipe', RecipeController::class)->only(['index', 'show']);
Route::resource('/my-recipe', MyRecipeController::class)
    ->middleware('jwt.auth')
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::resource('recipe.comment', CommentController::class)->scoped(['recipe' => 'comment'])
        ->middleware('jwt.auth');

Route::post('/recipe/{recipe}/my-rate', [\App\Http\Controllers\RateController::class, 'store'])
    ->middleware('jwt.auth');
Route::get('/recipe/{recipe}/my-rate', [\App\Http\Controllers\RateController::class, 'show'])
    ->middleware('jwt.auth');

