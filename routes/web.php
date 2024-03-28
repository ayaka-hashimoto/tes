<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DrinksController;


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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/regist',[App\Http\Controllers\ArticleController::class, 'registSubmit'])->name('submit');

//商品一覧
Route::get('/itiran', [App\Http\Controllers\DrinksController::class, 'index'])->name('itiran');

//新規登録
Route::get('/NewItemAdd', [App\Http\Controllers\DrinksController::class, 'showNewItemForm'])->name('NewItemAdd');
Route::post('/NewItemAdd', [App\Http\Controllers\DrinksController::class, 'showNewItemForm'])->name('NewItemAdd.store');

//詳細画面
Route::get('/syousai/{id}', [App\Http\Controllers\DrinksController::class, 'showsyousai'])->name('syousai');

//商品情報修正
Route::get('/edit/{id}', [App\Http\Controllers\DrinksController::class, 'showedit'])->name('edit');
//修正した情報をデータベースにおくる
Route::put('/edit/{id}', [App\Http\Controllers\DrinksController::class, 'update'])->name('edit.update');

//情報削除
Route::delete('/drinks/{id}', [App\Http\Controllers\DrinksController::class, 'destroy'])->name('drinks.destroy');