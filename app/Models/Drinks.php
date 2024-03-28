<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class Drinks extends Model
{
    public function getDrinks() {
        // テーブルからデータを取得
        $drinks = DB::table('drinks')->get();
        return $drinks;
    }

    //登録
    public function registNewItemAdd($image_path){
        DB::table('Drinks')->insert([
            'image_path' => $image_path
        ]);
    }
    protected $fillable = ['syouhinnmei', 'kakaku', 'zaikosuu', 'gyousya', 'komennto', 'image'];

    public $timestamps = false;


    public function registDrinks($data) {
        // 登録処理
       DB::table('Drinks')->insert([
        'image_path' => $image_path
      
    ]);
    }

    

}


