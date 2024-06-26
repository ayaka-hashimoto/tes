<html>
<head>
    <meta charset="UTF-8">
    <title>新商品登録</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <h1>商品登録</h1>
    <form action="{{ route('NewItemAdd.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <p><label for="image">商品画像:</label></p>
    <input type="file" name="image">
    
    <p><label for="product_name">*商品名:</label></p>
    <input type="text" id="product_name" name="product_name" required>
    
    <p> <label for="price">*価格:</label></p>
    <input type="number" id="price" name="price" required>
    
    <p><label for="stock">*在庫数:</label></p>
    <input type="number" id="stock" name="stock" required>
    
    <p><label for="company_name">*メーカー名:</label></p>
<select id="company_name" name="company_id">
    <option value="">--選択してください--</option>
    @foreach($companies as $company)
        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
    @endforeach
</select>

    <p><label for="comment">コメント:</label></p>
    <input type="text" id="comment" name="comment">
    
    <p>*は入力必須項目</p>

    <p><input type="submit" value="商品登録"></p>
</form>

<hr>
<a href="{{ route('itiran') }}" class="button">商品一覧</a>

</body>
</html>

