<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>在庫管理システム 商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>
<body>

<div class="container">
<h1>商品一覧</h1>

<form action="{{ route('itiran') }}" method="get">
    <label for="keyword">キーワード検索:</label>
    <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}">
    <label for="maker">メーカー名で絞り込み:</label>
    <input type="text" id="maker" name="maker" value="{{ request('maker') }}">
    <input type="submit" value="検索">
</form>

<table id="dataTable" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>メーカー名</th>
            <th>コメント</th>
            <th>商品情報詳細</th>
            <th>商品削除</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($products as $product)
    <tr data-id="{{ $product->id }}">
        <td>{{ $product->id }}</td>
        <td><img src="{{ asset($product->img_path) }}" style='width: 50px; height: 50px;'></td>
        <td>{{ $product->product_name }}</td>
        <td>{{ $product->price }}円</td>
        <td>{{ $product->stock }}</td>
        <td>{{ $product->company_name }}</td>
        <td>{{ $product->comment }}</td>
        <td>
            <form action="{{ route('syousai', $product->id) }}" method="GET">
                <button type="submit">詳細</button>
            </form>
        </td>
        <td style="text-align:center">
            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick='return confirm("削除しますか？")'>削除</button>
            </form>
        </td>
    </tr>
@endforeach
    </tbody>
</table>

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="detailContent"></p>
    </div>
</div>

<hr>
<a href="{{ route('NewItemAdd') }}" class="button">新規登録</a>
<a href="{{ route('itiran') }}" class="button">一覧に戻る</a>

</body>
</html>