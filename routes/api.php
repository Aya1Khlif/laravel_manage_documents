<?php

use App\Http\Controllers\Authapi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//add new comment
Route::post('/comments', 'App\Http\Controllers\CommentController@store');
//view all comments
Route::get('/documents/{document}/comments', 'App\Http\Controllers\CommentController@index');
//view one comment to document
Route::get('/documents/{document}/comments', 'App\Http\Controllers\CommentController@index');
//add new documents
Route::post('/documents', 'App\Http\Controllers\DocumentController@store');
//view all documents
Route::get('/documents', 'App\Http\Controllers\DocumentController@index');
//view one document
Route::get('/documents/{id}', 'App\Http\Controllers\DocumentController@show');
//update document
Route::put('/documents/{id}', 'App\Http\Controllers\DocumentController@update');
//delete document
Route::delete('/documents/{id}', 'App\Http\Controllers\DocumentController@destroy');

