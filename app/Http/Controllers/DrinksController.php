<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Drinks; 
use Exception; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;


class DrinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(){
        $model = new Drinks();
        $drinks = $model->getDrinks();
    }

    //絞り込み
    public function index(Request $request){
        $query = DB::table('drinks');

        $query->when($request->filled('keyword'), function ($query) use ($request) {
            $keyword = $request->keyword;
            return $query->where('syouhinnmei', 'like', '%' . $keyword . '%')
                         ->orWhere('kakaku', 'like', '%' . $keyword . '%')
                         ->orWhere('zaikosuu', 'like', '%' . $keyword . '%')
                         ->orWhere('komennto', 'like', '%' . $keyword . '%');
        });

        $query->when($request->filled('maker'), function ($query) use ($request) {
            $maker = $request->maker;
            return $query->where('gyousya', 'like', '%' . $maker . '%');
        });

        $drinks = $query->get();
        return view('drinks.itiran_viwe', ['drinks' => $drinks]);   
    }
    
    //新規登録
    public function showNewItemForm(Request $request) {
        if ($request->isMethod('post')) {
            $request->validate([
                'syouhinnmei' => 'required',
                'kakaku' => 'required|numeric',
                'zaikosuu' => 'required|numeric',
                'gyousya' => 'required',
                'komennto' => 'nullable',
                'image' => 'nullable|image',
            ]);
    
            $drink = new Drinks();
            $drink->syouhinnmei = $request->input('syouhinnmei');
            $drink->kakaku = $request->input('kakaku');
            $drink->zaikosuu = $request->input('zaikosuu');
            $drink->gyousya = $request->input('gyousya');
            $drink->komennto = $request->input('komennto');
    
            $image_path = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file_name = $image->getClientOriginalName();
                $image->storeAs('public/images', $file_name);
                $image_path = 'storage/images/' . $file_name;
                $drink->image_path = $image_path;
            }
    
            DB::beginTransaction();
            try {
                $drink->save();
                DB::commit();
                return redirect()->route('itiran')->with('success', '商品情報が登録されました。');
            } catch (Exception $e) {
                DB::rollBack();
                // Handle the exception, e.g., log it or return an error response
                return redirect()->back()->withErrors(['error' => '商品情報の登録に失敗しました。']);
            }
        }
    
        return view('drinks.NewItemAdd');
    }
    
    //商品詳細
    public function showsyousai($id) {
        $drink = Drinks::find($id);
        return view('drinks.syousai', compact('drink'));
    }

    //商品情報編集
    public function showedit($id)
    {
        $drinks = Drinks::find($id);
        return view('drinks.edit', compact('drinks'));
    }
    

    //更新した情報をデータベースに送る
    public function update(Request $request, $id)
{
    $request->validate([
        'syouhinnmei' => 'required',
        'kakaku' => 'required|numeric',
        'zaikosuu' => 'required|numeric',
        'gyousya' => 'required',
        'komennto' => 'nullable',
        'image' => 'nullable|image',
    ]);

    $drink = Drinks::findOrFail($id);
    $drink->syouhinnmei = $request->input('syouhinnmei');
    $drink->kakaku = $request->input('kakaku');
    $drink->zaikosuu = $request->input('zaikosuu');
    $drink->gyousya = $request->input('gyousya');
    $drink->komennto = $request->input('komennto');

    if ($request->hasFile('image')) {
       
        if ($drink->image_path) {
            Storage::delete('public/' . $drink->image_path);
        }

        //新しい画像登録と、前のデータ削除
        $image = $request->file('image');
        $file_name = $image->getClientOriginalName();
        $image_path = $image->storeAs('images', $file_name, 'public');
        $drink->image_path = 'storage/' . $image_path;
    }

    if (!$request->hasFile('image') && !$drink->image_path) {
        $drink->image_path = 'default/image/path.jpg';
    }

    $drink->save();

    return redirect()->route('itiran')->with('success', '商品情報が更新されました。');
}


    //商品削除
    public function destroy($id)
{
    $drink = Drinks::find($id);
    if ($drink) {
        $drink->delete();

        $max = DB::table('drinks')->max('id') + 1;
        DB::statement("ALTER TABLE drinks AUTO_INCREMENT = $max");

        return redirect()->route('itiran')->with('success', $drink->syouhinnmei . 'を削除しました');
    }
    return response()->json([
        'error' => 'Record not found!'
    ]);
}


public function deleteDrink($id) {
    DB::table('drinks')->where('id', $id)->delete();
    $this->destroy();
}


public function registSubmit(Request $request) {

    // トランザクション開始
    DB::beginTransaction();

    try {
        // 登録処理呼び出し
        $model = new Drinks ();
        $model->registDrinks($request);
        DB::commit();
    } catch (\Exception $e) {
        DB::rollback();
        return back();
    }

    // 処理が完了したらregistにリダイレクト
    return redirect(route('regist'));
}



}

  