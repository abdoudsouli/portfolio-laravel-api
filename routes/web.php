<?php

use Illuminate\Support\Facades\Route;

//welcome
Route::get('/', function () {
    return view('welcome');
});

/*use App\http\Controllers\TestController;



//id req
Route::get('/profile/{id}',function($id){
return $id;
});

//id not req
Route::get('/profile/info/{id?}',function($id = null){
return $id;
});

//where condition
Route::get('/user/{name}', function ($name) {
return $name;
})->where('name', '[A-Za-z]+');

//where group condition
Route::get('/user/{id}/{name}', function (string $id, string $name) {
return 'Name : '.$name.' Id is : '.$id;
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);

//group middleware
Route::middleware(['adm', 'auth'])->group(function () {
    Route::get('/logout', function () {});
    Route::get('/user/profile', function () {});
});

//group prefix
Route::prefix('dach')->group(function () {
    Route::get('/', function () {
return 'index dach';
    });
    Route::get('/profile', function () {
return 'index profile';
    });
});

//call to controllers
Route::get('/getcontroller',[TestController::class,'index']);

//call to controllers validatour test id
Route::get('/getcontrollerid/{id}',[TestController::class,'getid']);
*/
