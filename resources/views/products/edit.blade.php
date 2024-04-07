<html>
<head>
    <meta charset="UTF-8">
    <title>商品情報修正</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
   </head>
<body>
    <h1>商品情報修正</h1>
    <form method="POST" action="{{ route('edit.update', $product->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <p><label for="image">商品画像:</label></p>
    <input type="file" name="image">

    <p><label for="product_name">*商品名:</label></p>
    <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
    
    <p><label for="price">*価格:</label></p>
    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required>
    
    <p><label for="stock">*在庫数:</label></p>
    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
    
    <p><label for="company_name">*メーカー名:</label></p>
    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $product->company_name) }}" required>
    
    <p><label for="comment">コメント:</label></p>
    <input type="text" id="comment" name="comment" value="{{ old('comment', $product->comment) }}">

    <p>*は入力必須項目</p>

    <button type="submit">情報更新</button>
</form>

<hr>
<a href="{{ route('itiran') }}" class="button">商品一覧</a>

</body>
</body>
</html>

