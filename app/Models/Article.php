<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;

    public function getList() {
        // テーブルからデータを取得
        $articles = DB::table('drinks')->get();

        return $articles;
    }
}

