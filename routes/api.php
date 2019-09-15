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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

  'middleware' => 'api',
  'prefix' => 'auth'

], function ($router) {

  Route::post('login', 'AuthController@login');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::post('me', 'AuthController@me');
});

Route::group([

  'prefix' => 'blog-post'

], function ($router) {
  Route::get('', 'BlogPostController@show');
  Route::get('index', 'BlogPostController@index');
});

Route::group([

  'middleware' => 'api',
  'prefix' => 'blog-post'

], function ($router) {
  Route::post('', 'BlogPostController@store');
  Route::put('', 'BlogPostController@update');
  Route::delete('', 'BlogPostController@destroy');
});