<?php

use Illuminate\Http\Request;
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
    return redirect('leads');
});

Route::group(['middleware' => 'unAuthenticated', 'namespace' => 'Auth'], function () {
    Route::get('login', 'LoginController@login');
    Route::post('authenticate-user', 'LoginController@autheticateUser');
    Route::get('oauth', 'LoginController@getFreelancerAccessToken');
});

Route::group(['middleware' => ['authenticated']], function () {
    Route::get('user-details', 'Auth\LoginController@getFreelancerUserDetails');
    Route::get('logout', 'Auth\LoginController@logout');

    Route::group(['prefix' => 'leads'], function () {
        Route::get('/', 'LeadController@index');
        Route::get('my-leads', 'LeadController@myLeads');
        Route::get('{id}/details', 'LeadController@details');
        Route::get('{id}/proposals', 'LeadController@proposals');
        Route::post('place-bid', 'LeadController@placeBid');
        Route::post('get-leads', 'LeadController@getLeads');
        Route::get('retract-bid/{bid_id}/{project_id}', 'LeadController@retractBid');
        Route::get('bid-statuses', 'LeadController@checkBidStatus');
        Route::get('update-watch-status/{id}', 'LeadController@updateWatchStatus');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@index');
        Route::get('add', 'UserController@add');
        Route::post('store', 'UserController@store');
        Route::get('details/{id}', 'UserController@details');
        Route::post('update', 'UserController@update');
        Route::post('deactivate', 'UserController@deactivate');
        Route::post('activate', 'UserController@activate');
    });


    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', 'MessagingController@index');
        Route::get('messages', 'MessagingController@messages');
        Route::post('send', 'MessagingController@sendMessage');
        Route::post('assign', 'MessagingController@assignThread');
        Route::post('download-attachment', 'MessagingController@downloadAttchment');
        Route::get('unread-messages', 'MessagingController@getUnreadMessages');
        // Route::post('search-threads', 'MessagingController@searchThreads');
    });

    Route::get('profile/edit', 'SettingController@profileEdit');
    Route::post('profile/update', 'SettingController@profileUpdate');
    Route::post('profile/update-password', 'SettingController@updatePassword');
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingController@index');
        Route::post('update', 'SettingController@update');
        Route::group(['prefix' => 'accounts'], function () {
            Route::get('update-account/{id}', 'SettingController@updateUserActiveAccount');
            Route::get('add', 'SettingController@addAccount');
            Route::post('store', 'SettingController@storeAccount');
            Route::get('deactivate/{id}', 'SettingController@deactivateAccount');
            Route::get('activate/{id}', 'SettingController@activateAccount');
            Route::get('edit/{id}', 'SettingController@editAccount');
            Route::post('update', 'SettingController@updateAccount');
            Route::get('delete/{id}', 'SettingController@deleteAccount');
        });
    });
});
