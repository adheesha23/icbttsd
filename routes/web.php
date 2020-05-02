<?php

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

Auth::routes();

Route::get('/admin', 'AdminController@index')->name('admin')->middleware('admin');
Route::get('/manager', 'ManagerController@index')->name('manager')->middleware('manager');
Route::get('/auditor', 'AuditorController@index')->name('auditor')->middleware('auditor');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/auditor', 'HomeController@index')->name('home');


Route::get('/reports', function () {
    return view('report');
});

Route::get('/charts', function () {
    return view('chart');
});

//Route::get('/listings', 'Content\ContentController@index')->name('contents.index');

Route::get('/sales', 'ReportsController@sales')->name('reports.sales');

Route::get('/box-office-summary', 'ReportsController@boxOfficeSummary');
Route::post('/box-office-summary', 'ReportsController@boxOfficeSummary')->name('reports.boxofficesummary');
Route::get('/sales/pdf','ReportsController@export_pdf');
