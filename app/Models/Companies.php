<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company; 

class Companies extends Model
{
    use HasFactory;

    protected $fillable = ['name']; //メーカー名を保存できるようにする
    public function getProducts()
    {

    $Companies=DB::table('Companies')->get();
    return $Companies;

    }
    

    //companiesテーブルと結合
    public function products()
    {
        return $this->hasMany(Products::class, 'company_id');
    }


    //絞り込み
    public function companies()
{
    return $this->belongsTo(companies::class);
}
}
    
    

