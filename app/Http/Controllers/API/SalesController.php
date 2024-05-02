<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function buy(Request $request)
    {
        // リクエストから購入する商品の情報を取得
        $ProductsId = $request->input('product_id');
        
        // 商品が存在するか確認
        $Products = Products::find($ProductsId);
        if (!$Products) {
            return response()->json(['message' => '商品が見つかりません'], 404);
        }
        
        // 在庫があるか確認
        if ($Products->stock <= 0) {
            return response()->json(['message' => '在庫がありません'], 400);
        }
        
        // 在庫を減算し、保存
        $Products->stock--;
        $Products->save();
        
        // 購入成功のレスポンスを返す
        return response()->json(['message' => '購入が完了しました']);
    }
}
