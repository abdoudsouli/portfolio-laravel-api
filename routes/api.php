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
Route::get('/user',[UserController::class,'index']);
Route::get('/user/{id}',[UserController::class,'getbyid']);
Route::apiResource('services',ServicesController::class);
Route::apiResource('skills',SkillsController::class);
Route::apiResource('project',ProjectController::class);
Route::apiResource('resume',ResumeController::class);

Route::post('resumedeletedrestore',ResumeController::class,'resumedeletedrestore');
Route::post('resumedeleted',ResumeController::class,'resumedeleted');

Route::get('projectdeleted',[ProjectController::class,'projectdeleted']);
Route::post('projectdeletedrestore',[ProjectController::class,'projectdeletedrestore']);
});


Route::middleware(['auth:sanctum','checkpassword'])->group(function(){
Route::get('myproject',[ProjectController::class,'meproject']);
});


