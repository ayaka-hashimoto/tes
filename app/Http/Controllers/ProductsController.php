<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Products; 
use App\Models\Companies; 
use Exception; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;


class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function getList(Request $request): View
{
    $query = Products::query()->with('company');

    // キーワード検索
    $query->when($request->filled('keyword'), function ($query) use ($request) {
        $keyword = $request->keyword;
        return $query->where('product_name', 'like', '%' . $keyword . '%')
            ->orWhere('price', 'like', '%' . $keyword . '%')
            ->orWhere('stock', 'like', '%' . $keyword . '%')
            ->orWhere('comment', 'like', '%' . $keyword . '%');
    });

    // メーカー名絞り込み
    $query->when($request->filled('company_id'), function ($query) use ($request) {
        $query->where('company_id', $request->company_id);
    });

    // 価格の絞り込み
    $query->when($request->filled('min_price') || $request->filled('max_price'), function ($query) use ($request) {
        $query->whereBetween('price', [
            $request->filled('min_price') ? $request->min_price : 0,
            $request->filled('max_price') ? $request->max_price : PHP_INT_MAX,
        ]);
    });

    // 在庫数の絞り込み
    $query->when($request->filled('min_stock') || $request->filled('max_stock'), function ($query) use ($request) {
        $query->whereBetween('stock', [
            $request->filled('min_stock') ? $request->min_stock : 0,
            $request->filled('max_stock') ? $request->max_stock : PHP_INT_MAX,
        ]);
    });

    $products = $query->get();
    $companies = Companies::all();

    return view('products.itiran', compact('products', 'companies'));
}
  
    //新規登録
    public function showNewItemForm(Request $request)
    {
        $companies = Companies::all();
        
        if ($request->isMethod('post')) {
            $request->validate([
                'product_name' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'company_id' => 'required|exists:companies,id',
                'comment' => 'nullable',
                'image' => 'nullable|image',
            ]);
    
            $productData = [
                'product_name' => $request->input('product_name'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'company_id' => $request->input('company_id'),
                'comment' => $request->input('comment'),
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file_name = $image->getClientOriginalName();
                $image->storeAs('public/images', $file_name);
                $img_path = 'storage/images/' . $file_name;
                $productData['img_path'] = $img_path;
            }

            DB::beginTransaction();
            try {
                $product = Products::create($productData);
                DB::commit();
                return redirect()->route('itiran')->with('success', '商品情報が登録されました。');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => '商品情報の登録に失敗しました。']);
            }
        }
    
        return view('products.NewItemAdd', compact('companies'));
    }
    
    //商品詳細
    public function showsyousai($id) {
        $product = Products::find($id); // 特定の商品IDに基づいて商品を検索
        return view('products/syousai', compact('product')); // 商品をビューに渡す
    }

    //商品情報編集
    public function showedit($id)
    {
        $product = Products::find($id);
        $companies = Companies::all(); 
        return view('products.edit', compact('product', 'companies'));
    }
    

    //更新した情報をデータベースに送る
    public function update(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|numeric',
        'company_id' => 'required',
        'comment' => 'nullable',
        'image' => 'nullable|image',
    ]);

    $product = Products::findOrFail($id);
    $product->product_name = $request->input('product_name');
    $product->price = $request->input('price');
    $product->stock = $request->input('stock');
    $product->comment = $request->input('comment');

    // 会社情報を取得
    $company = Companies::findOrFail($request->input('company_id'));
    $product->company_id = $company->id;

    // 画像がアップロードされている場合の処理
    if ($request->hasFile('image')) {
        // 以前の画像を削除
        if ($product->img_path && Storage::exists($product->img_path)) {
            Storage::delete($product->img_path);
        }

        // 新しい画像を保存
        $image = $request->file('image');
        $file_name = $image->getClientOriginalName();
        $img_path = $image->storeAs('images', $file_name, 'public');
        $product->img_path = 'storage/' . $img_path;
    }

    // 画像がアップロードされていない場合の処理
    if (!$request->hasFile('image') && !$product->img_path) {
        $product->img_path = 'default/image/path.jpg';
    }

    // 商品情報を保存
    $product->save();

    return redirect()->route('itiran')->with('success', '商品情報が更新されました。');
}


    //商品削除
    public function destroy($id)
{
    $product = Products::find($id);
    if ($product) {
        $product->delete();

        $max = DB::table('products')->max('id') + 1;
        DB::statement("ALTER TABLE products AUTO_INCREMENT = $max");

        return redirect()->route('itiran')->with('success', $product->product_name . 'を削除しました');
    }
    return response()->json([
        'error' => 'Record not found!'
    ]);
}


    public function deleteProducts($id) {
        DB::table('products')->where('id', $id)->delete();
        return redirect()->route('itiran')->with('success', '商品情報が削除されました。');
}

    public function registSubmit(Request $request) {
    DB::beginTransaction();

    try {
        $model = new Products();
        $model->registproducts($request);
        DB::commit();
    } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors(['error' => '登録に失敗しました。']);
    }

    return redirect(route('regist'))->with('success', '登録が完了しました。');
}

//検索　上限下限追加
public function search(Request $request)
{
    $keyword = $request->input('keyword');
    $companyId = $request->input('company_id');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');
    $minStock = $request->input('min_stock');
    $maxStock = $request->input('max_stock');

    $products = Products::query();

    if ($keyword) {
        $products->where('product_name', 'like', '%' . $keyword . '%');
    }

    if ($companyId) {
        $products->where('company_id', $companyId);
    }

    if ($minPrice) {
        $products->where('price', '>=', $minPrice);
    }

    if ($maxPrice) {
        $products->where('price', '<=', $maxPrice);
    }

    if ($minStock) {
        $products->where('stock', '>=', $minStock);
    }

    if ($maxStock) {
        $products->where('stock', '<=', $maxStock);
    }

    $products = $products->get();

    // 必要に応じて、追加のデータをレスポンスに含める
    return response()->json($products);
}

}



  