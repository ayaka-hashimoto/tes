<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class Products extends Model
{
    use HasFactory;

    protected $fillable = ['product_name', 'price', 'stock', 'company_id', 'comment', 'img_path'];

    public $timestamps = false;

    public function getProducts() {
        // テーブルからデータを取得
        $products = DB::table('products')
            ->join('companies','products.company_id','=','companies.id')
            ->select('products.*','companies.name as company_name')
            ->paginate(5);
        return $products;
    }

    //Productモデルがcompanysテーブルとリレーション関係を結ぶ為のメソッド
    public function company()
    {
        return $this->belongsTo(Companies::class, 'company_id', 'id');
    }

    //登録
    public function registNewItemAdd($img_path){
        DB::table('products')->insert([
            'img_path' => $img_path
        ]);
    }

    public function registProducts($data) {
        // 登録処理
        DB::table('products')->insert([
            'img_path' => $data['img_path']
        ]);
    }

    //商品登録
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
            \Log::info('Request data:', $request->all());
            
            $company = Companies::firstOrCreate(['name' => $request->company_name]);
            $productData = [
                'product_name' => $request->input('product_name'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'company_id' => $company->id, 
                'comment' => $request->input('comment'),
            ];
    
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file_name = $image->getClientOriginalName();
                $image->storeAs('public/images', $file_name);
                $productData['img_path'] = 'storage/images/' . $file_name;
            }
    
            DB::beginTransaction();
            try {
                $product = Products::create($productData);
                DB::commit();
                return redirect()->route('itiran')->with('success', '商品情報が登録されました。');
            } catch (\Exception $e)  {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => '商品情報の登録に失敗しました。']);
            }
        }
    
        return view('products.NewItemAdd');
    }
}