<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>在庫管理システム 商品一覧</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
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
    <input type="button" id="resetBtn" value="全件表示に戻す">
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
    @foreach ($drinks as $drink)
    <tr data-id="{{ $drink->id }}">
        <td>{{ $drink->id }}</td>
        <td><img src="{{ asset($drink->image_path) }}" style='width: 50px; height: 50px;'></td>
        <td>{{ $drink->syouhinnmei }}</td>
        <td>{{ $drink->kakaku }}円</td>
        <td>{{ $drink->zaikosuu }}</td>
        <td>{{ $drink->gyousya }}</td>
        <td>{{ $drink->komennto }}</td>
        <td><button class='detail-btn' data-id='{{ $drink->id }}'>詳細</button></td>
        <td style="text-align:center">
            <form action="{{ route('drinks.destroy', $drink->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick='return confirm("削除しますか？");'>削除</button>
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

<script>
    var baseUrl = "{{ url('/') }}";

    document.getElementById('resetBtn').addEventListener('click', function() {
        document.getElementById('keyword').value = '';
        document.getElementById('maker').value = '';
        document.querySelector('form').submit();
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.detail-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var id = e.target.getAttribute('data-id');
                window.location.href = `${baseUrl}/syousai/${id}`;
            });
        });
    });

    

    $(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    var id = $(this).closest('form').data('id');
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/drinks/' + id,
        type: 'DELETE',
        data: {
            "_token": token,
        },
        success: function(response) {
            if (response.success) {
                $('tr[data-id="' + id + '"]').remove();
            } else {
                alert(response.error);
            }
        },
        error: function(error) {
            console.error(error);
        }
    });
});
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
    margin: 0 auto; /* Center the container */
}

</style>

<hr>
<a href="{{ route('NewItemAdd') }}" class="button">新規登録</a>

</body>
</html>