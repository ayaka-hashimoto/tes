<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>在庫管理システム　修正</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>商品内容修正</h1>

    <?php
    include 'itirann.php';

    // GETパラメータから商品IDを取得
    $id = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($id)) {
    // 商品IDに基づいて該当する商品の情報を取得
    $sql = "SELECT * FROM drinks WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        
    } else {
        echo "該当する商品が見つかりませんでした。";
    }
} else {
    echo "商品IDが指定されていません。";
}

    ?>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="detailContent"></p>
        </div>
    </div>

    <!-- スクリプトタグの配置 -->
    <script>
    </script>


    <form action="update_item.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">
        <label for="image_path">商品画像:</label>
        <input type="text" id="image_path" name="image_path" value="<?php echo htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="syouhinnmei">商品名:</label>
        <input type="text" id="syouhinnmei" name="syouhinnmei" value="<?php echo htmlspecialchars($row['syouhinnmei'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="kakaku">価格:</label>
        <input type="text" id="kakaku" name="kakaku" value="<?php echo htmlspecialchars($row['kakaku'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="zaikosuu">在庫数:</label>
        <input type="text" id="zaikosuu" name="zaikosuu" value="<?php echo htmlspecialchars($row['zaikosuu'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="gyousya">メーカー名:</label>
        <input type="text" id="gyousya" name="gyousya" value="<?php echo htmlspecialchars($row['gyousya'], ENT_QUOTES, 'UTF-8'); ?>"><br>
        <label for="komennto">コメント:</label>
        <textarea id="komennto" name="komennto"><?php echo htmlspecialchars($row['komennto'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>
        <input type="submit" value="更新">
        
    </form>

    <hr>
    <p><input type="button" onclick="location.href='index.php'" value="商品一覧"></p>
    <p><input type="button" onclick="location.href='NewItemAdd.html'" value="商品新規登録"></p>
</body>
</html>