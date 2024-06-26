<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


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
Route::get('/itiran', [App\Http\Controllers\ProductsController::class, 'getList'])->name('itiran');


//新規登録
Route::get('/NewItemAdd', [App\Http\Controllers\ProductsController::class, 'showNewItemForm'])->name('NewItemAdd');
Route::post('/NewItemAdd', [App\Http\Controllers\ProductsController::class, 'showNewItemForm'])->name('NewItemAdd.store');


//詳細画面
Route::get('/products/syousai/{id}', [App\Http\Controllers\ProductsController::class, 'showsyousai'])->name('syousai');

//商品情報修正
Route::get('/edit/{id}', [App\Http\Controllers\ProductsController::class, 'showedit'])->name('edit');
//修正した情報をデータベースにおくる
Route::put('/edit/{id}', [App\Http\Controllers\ProductsController::class, 'update'])->name('edit.update');

//情報削除
Route::delete('/products/{id}', [App\Http\Controllers\ProductsController::class, 'destroy'])->name('products.destroy');
