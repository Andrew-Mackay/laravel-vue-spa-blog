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

// todo check middleware actually works
Route::group([

  'middleware' => 'api',
  'prefix' => 'blog-post'

], function ($router) {
  Route::get('edit/{blogPost}', 'BlogPostController@showEdit');
  Route::post('', 'BlogPostController@store');
  Route::put('{blogPost}', 'BlogPostController@update');
  Route::delete('{blogPost}', 'BlogPostController@destroy');
  Route::group(
    ['prefix' => 'drafts'],
    function ($router) {
      Route::get('index', 'BlogPostController@getDrafts');
      Route::put('{blogPost}', 'BlogPostController@publishDraft');
    }
  );
});

Route::group([

  'prefix' => 'blog-post'

], function ($router) {
  Route::get('index', 'BlogPostController@index');
  Route::get('{blogPost}', 'BlogPostController@show');
});

