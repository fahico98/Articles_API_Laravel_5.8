<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
   return $request->user();
});

// List articles.
Route::get("articles", "ArticleController@index");

// Show single article.
Route::get("article/{id}", "ArticleController@show");

// Create new article.
Route::get("article/create", "ArticleController@create");

// Store the data of new article.
Route::post("article/store", "ArticleController@store");

// Store the data of an updated article.
Route::put("article/update", "ArticleController@store");

// Edit an existent article.
Route::get("article/edit/{id}", "ArticleController@edit");

// Update the article data.
//Route::post("article/update/{id}", "ArticleController@update");

// Delete article.
Route::delete("article/destroy/{id}", "ArticleController@destroy");
