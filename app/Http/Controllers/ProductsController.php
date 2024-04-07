<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Products; 
use Exception; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;


class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(){
        $model = new Products();
        $products = $model->getProducts();
    }

    //絞り込み
    public function index(Request $request){
        $query = DB::table('products');

        $query->when($request->filled('keyword'), function ($query) use ($request) {
            $keyword = $request->keyword;
            return $query->where('product_name', 'like', '%' . $keyword . '%')
                         ->orWhere('price', 'like', '%' . $keyword . '%')
                         ->orWhere('stock', 'like', '%' . $keyword . '%')
                         ->orWhere('comment', 'like', '%' . $keyword . '%');
        });

        $query->when($request->filled('maker'), function ($query) use ($request) {
            $maker = $request->maker;
            return $query->where('company_name', 'like', '%' . $maker . '%');
        });

        $products = $query->get();
        return view('products.itiran_viwe', ['products' => $products]);   
    }
    
    //新規登録
    public function showNewItemForm(Request $request) {
        if ($request->isMethod('post')) {
            $request->validate([
                'product_name' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'company_name' => 'required',
                'comment' => 'nullable',
                'image' => 'nullable|image',
            ]);
    
            $products = new Products();
            $products->product_name = $request->input('product_name');
            $products->price = $request->input('price');
            $products->stock = $request->input('stock');
            $products->company_name = $request->input('company_name');
            $products->comment = $request->input('comment');
    
            $img_path = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file_name = $image->getClientOriginalName();
                $image->storeAs('public/images', $file_name);
                $img_path = 'storage/images/' . $file_name;
                $products->img_path = $img_path;
            }
    
            DB::beginTransaction();
            try {
                $products->save();
                DB::commit();
                return redirect()->route('itiran')->with('success', '商品情報が登録されました。');
            } catch (Exception $e) {
                DB::rollBack();
                // Handle the exception, e.g., log it or return an error response
                return redirect()->back()->withErrors(['error' => '商品情報の登録に失敗しました。']);
            }
        }
    
        return view('products.NewItemAdd');
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
    return view('products.edit', compact('product'));
    }
    

    //更新した情報をデータベースに送る
    public function update(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|numeric',
        'company_name' => 'required',
        'comment' => 'nullable',
        'image' => 'nullable|image',
    ]);

    $product = Products::findOrFail($id);
    $product->product_name = $request->input('product_name');
    $product->price = $request->input('price');
    $product->stock = $request->input('stock');
    $product->company_name = $request->input('company_name');
    $product->comment = $request->input('comment');

    if ($request->hasFile('image')) {
       
        if ($product->img_path) {
            Storage::delete('public/' . $product->img_path);
        }

        //新しい画像登録と、前のデータ削除
        $image = $request->file('image');
        $file_name = $image->getClientOriginalName();
        $img_path = $image->storeAs('images', $file_name, 'public');
        $product->img_path = 'storage/' . $img_path;
    }

    if (!$request->hasFile('image') && !$product->img_path) {
        $product->img_path = 'default/image/path.jpg';
    }

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
    $this->destroy();
}


public function registSubmit(Request $request) {

    // トランザクション開始
    DB::beginTransaction();

    try {
        // 登録処理呼び出し
        $model = new Products ();
        $model->registproducts($request);
        DB::commit();
    } catch (\Exception $e) {
        DB::rollback();
        return back();
    }

    // 処理が完了したらregistにリダイレクト
    return redirect(route('regist'));
}



}

  