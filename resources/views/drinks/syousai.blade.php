<!DOCTYPE html>
<html lang="ja">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>在庫管理システム 詳細</title>
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
  
    <tr data-id="{{ $drink->id }}">
    <td>{{ $drink->id }}</td>
    <td><img src="{{ asset($drink->image_path) }}" style='width: 50px; height: 50px;'></td>
    <td>{{ $drink->syouhinnmei }}</td>
    <td>{{ $drink->kakaku }}円</td>
    <td>{{ $drink->zaikosuu }}</td>
    <td>{{ $drink->gyousya }}</td>
    <td>{{ $drink->komennto }}</td>
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

<style>
th, td {
    border: 1px solid #0b0b0b;
    padding: 8px;
}

th {
    background-color: #f3df05;
    text-align:center;
}

td {
    text-align: center;
    vertical-align: middle;
}

.button { 
    background-color: #4CAF50; 
    border: none; 
    color: white; 
    padding: 5px 15px; 
    text-align: center; 
    text-decoration: none; 
    display: inline-block; 
    font-size: 16px; 
    margin: 4px 2px; 
    cursor: pointer; 
    transition: background-color 0.1s;
}

.button:hover {
    background-color: #1E4620; 
}

.container {
    width: 80%;
    margin: 0 auto;
}

#dataTable {
    border-collapse: collapse;
}

</style>

<hr>
<a href="{{ route('edit', ['id' => $drink->id]) }}" class="button">商品情報修正</a>
<a href="{{ route('NewItemAdd') }}" class="button">新規登録</a>
<a href="{{ route('itiran') }}" class="button">商品一覧</a>

</body>
</html> 