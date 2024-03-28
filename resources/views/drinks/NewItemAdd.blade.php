<html>
<head>
    <meta charset="UTF-8">
    <title>新商品登録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>商品登録</h1>
    <form action="{{ route('NewItemAdd.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <p><label for="image">商品画像:</label></p>
    <input type="file" name="image">
    
    <p><label for="syouhinnmei">*商品名:</label></p>
    <input type="text" id="syouhinnmei" name="syouhinnmei" required>
    
    <p> <label for="kakaku">*価格:</label></p>
    <input type="number" id="kakaku" name="kakaku" required>
    
    <p><label for="zaikosuu">*在庫数:</label></p>
    <input type="number" id="zaikosuu" name="zaikosuu" required>
    
    <p> <label for="gyousya">*メーカー名:</label></p>
    <input type="text" id="gyousya" name="gyousya" required>
    
    <p><label for="komennto">コメント:</label></p>
    <input type="text" id="komennto" name="komennto">
    
    <p>*は入力必須項目</p>

    <p><input type="submit" value="商品登録"></p>
</form>

<style>

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

</style>

<hr>
<a href="{{ route('itiran') }}" class="button">商品一覧</a>

</body>
</html>

