<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <head>
        <title>新商品登録</title>
        <meta charset="UTF-8">
        <meta name="robots" content="noindex" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="stylesheet" href="css/style.css">



        </style>
    </head>
    <body>
      <div class="base container">
        <div class="row">
          <h1>商品登録</h1>
        </div>
        <div class="row">
          <div class="card" id="card">
            <div class="card-block">
              <form action="NewItemAdd_check.php" method="post" enctype="multipart/form-data">
                <p>*商品名<input type="text" name="syouhinnmei" required></p>
                <p>*価格<input type="number" name="kakaku" min="0" value="0">円</p>
                <p>*在庫数<input type="number" name="zaikosuu" min="0" value="0"></p>
                <p>*メーカー名<input type="text" name="gyousya"></p>
                <p>画像<input type="file" name="upload_file" accept="image/*"></p>
                <p>*は入力必須項目</p>
                <p><input type="submit" value="登録"></p>
            </form>
            <hr>
            <p><input type="button" onclick="location.href='Drinks.html'"   value="メインメニュー"></p>
            <p><input type="button" onclick="location.href='NewItemAdd.html'" value="新商品登録"></p>
            <p><input type="button" onclick="location.href='ItemList.html'"   value="商品一覧"></p>
        </body>
                </form> 
                


       
    </body>
</html>
