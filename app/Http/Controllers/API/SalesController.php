<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Sales; 
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function buy(Request $request)
    {
        DB::beginTransaction(); // トランザクション開始

        try {
            // リクエストから購入する商品の情報を取得
            $productId = $request->input('product_id');
            
            // 商品が存在するか確認
            $product = Products::find($productId);
            if (!$product) {
                return response()->json(['message' => '商品が見つかりません'], 404);
            }
            
            // 在庫があるか確認
            if ($product->stock <= 0) {
                return response()->json(['message' => '在庫がありません'], 400);
            }
            
            // 在庫を減算し、保存
            $product->stock--;
            $product->save();
            
            // 購入記録を作成
            Sales::create([ 
                'product_id' => $productId,
                'amount' => 1, // 実際の購入数量に応じて修正
                // その他の購入情報を必要に応じて追加
            ]);

            DB::commit(); // トランザクションをコミット

            // 購入成功のレスポンスを返す
            return response()->json(['message' => '購入が完了しました']);
        } catch (\Exception $e) {
            DB::rollback(); // トランザクションをロールバック

            // エラーが発生した場合はエラーメッセージを返す
            return response()->json(['message' => '購入処理中にエラーが発生しました'], 500);
        }
    }
}
