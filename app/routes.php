<?php
Route::get('register', 'HomeController@register');
Route::get('login', 'HomeController@login');
Route::post('login', array('before' => 'csrf', 'uses' => 'HomeController@login'));
Route::post('register', array('before' => 'csrf', 'uses' => 'HomeController@register'));
Route::get('logout', 'HomeController@logout');
Route::get('acceptAll/{synToken}', 'HomeController@acceptAll');
Route::get('reject/{dateOffId}/{synToken}', 'HomeController@reject');

Route::group(array('before' => 'auth'), function() {
    Route::get('/', 'HomeController@logTask');
    Route::get('history/{date?}', 'HomeController@history');
    Route::get('reports/{date?}', 'HomeController@synthesize');
    Route::get('weekReports/{year}/{weekNumber?}', 'HomeController@weekReports');
    Route::get('projects', 'HomeController@registerProjects');
    Route::get('regDayOff', 'HomeController@registerDayOff');
    Route::get('assignReportDays', 'HomeController@assignReportDays');
});

Route::group(array('before' => 'authAjax'), function() {
    Route::group(array('before' => 'csrf'), function() {
        Route::post('logTask', 'AjaxController@logTask');
        Route::post('remind', 'AjaxController@remind');
        Route::post('sendAllReport', 'AjaxController@sendAllReport');
        Route::post('joinProject', 'AjaxController@joinProject');
        Route::post('leaveProject', 'AjaxController@leaveProject');
        Route::post('addDateOff', 'AjaxController@addDateOff');
        Route::post('removeDateOff', 'AjaxController@removeDateOff');
    });
    Route::get('getTasks/{projectId}/{date}', 'AjaxController@getTasks');
    Route::get('getDatesOff/{month}', 'AjaxController@getDatesOff');
});

Route::group(array('prefix' => 'admin'), function() {
    Route::get('user', function() {
        die('ADMIN USER');
    });
});