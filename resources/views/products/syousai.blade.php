<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>在庫管理システム 詳細</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<h1>商品詳細</h1>

<table id="dataTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>メーカー名</th>
            <th>コメント</th>
        </tr>
    </thead>
    <tbody>
  
    <tr data-id="{{ $product->id }}">
    <td>{{ $product->id }}</td>
    <td><img src="{{ asset($product->img_path) }}" style='width: 50px; height: 50px;'></td>
    <td>{{ $product->product_name }}</td>
    <td>{{ $product->price }}円</td>
    <td>{{ $product->stock }}</td>
    <td>{{ $product->company->company_name }}</td>
    <td>{{ $product->comment }}</td>
</tr>

    </tbody>
</table>

<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="detailContent"></p>
    </div>
</div>

<script>
var baseUrl = "{{ url('/') }}";
</script>

<hr>
<a href="{{ route('edit', ['id' => $product->id]) }}" class="button">商品情報修正</a>
<a href="{{ route('NewItemAdd') }}" class="button">新規登録</a>
<a href="{{ route('itiran') }}" class="button">商品一覧</a>

</body>
</html> 