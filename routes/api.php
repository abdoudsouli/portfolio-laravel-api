<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\SkillsController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\api\ServicesController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum','role:admin','checkpassword'])->group(function(){
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/profil',[UserController::class,'index']);
Route::post('/profil/update',[UserController::class,'update_profil']);
Route::post('/profil/changepassword',[UserController::class,'change_password']);
Route::post('/profil/avatar',[UserController::class,'avatar']);
Route::post('/profil/cv',[UserController::class,'uploadcv']);
Route::apiResource('services',ServicesController::class);
Route::apiResource('skills',SkillsController::class);
Route::apiResource('project',ProjectController::class);
Route::apiResource('resume',ResumeController::class);

Route::post('resumedeletedrestore',[ResumeController::class,'resumedeletedrestore']);
Route::get('resumedeleted',[ResumeController::class,'resumedeleted']);

Route::get('projectdeleted',[ProjectController::class,'projectdeleted']);
Route::post('projectdeletedrestore',[ProjectController::class,'projectdeletedrestore']);
});


Route::middleware(['auth:sanctum','checkpassword'])->group(function(){
Route::get('myproject',[ProjectController::class,'meproject']);
});


