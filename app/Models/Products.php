<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class Products extends Model
{
    public function getProducts() {
        // テーブルからデータを取得
        $products = DB::table('products')->get();
        return $products;
    }

    //登録
    public function registNewItemAdd($img_path){
        DB::table('products')->insert([
            'img_path' => $img_path
        ]);
    }
    protected $fillable = ['product_name', 'price', 'stock', 'company_name', 'comment', 'image'];

    public $timestamps = false;


    public function registProducts($data) {
        // 登録処理
       DB::table('products')->insert([
        'img_path' => $img_path
      
    ]);
    }

    

}


