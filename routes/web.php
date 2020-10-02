<?php
//DB::listen(function ($query) {var_dump($query->sql, $query->bindings);});

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function (){
    Route::post('/tweets', 'TweetController@store');
    Route::get('/tweets', 'TweetController@index')->name('home');

    Route::post('/profiles/{user:name}/follow', 'FollowsController@store');
    Route::get(
        '/profiles/{user:name}/edit',
        'ProfilesController@edit'
    )->middleware('can:edit,user'); // can we edit this wildcard named 'name'
});

Route::get('/profiles/{user:name}', 'ProfilesController@show')->name('profile');

Auth::routes();
