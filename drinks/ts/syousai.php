<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>在庫管理システム　詳細</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>商品詳細</h1>

<?php
include 'itirann.php';

// GETパラメータから商品IDを取得
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($id)) {
    // 商品IDに基づいて該当する商品の情報のみを取得
    $sql = "SELECT * FROM drinks WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // 結果を取得して表示
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // 商品情報の表示
        echo "<table border='1'>";
        echo "<tr><th>ID</th>
                 <th>商品画像</th>
                 <th>商品名</th>
                 <th>価格</th>
                 <th>在庫数</th>
                 <th>メーカー名</th>
                 <th>コメント</th>
              </tr>";
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td><img src='" . htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8') . "' style='width: 50px; height: 50px;'></td>";
        echo "<td>" . htmlspecialchars($row['syouhinnmei'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['kakaku'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['zaikosuu'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['gyousya'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['komennto'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "該当する商品が見つかりませんでした。";
    }
} else {
    echo "商品IDが指定されていません。";
}
?>
    
    <!-- モーダルのHTML構造 -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="detailContent"></p>
            <input type="button" onclick="location.href='syuusei.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>'" value="商品内容修正">
        </div>
    </div>

     <script>  
    </script>
</body>
</html>

<hr>
 <p><input type="button" onclick="location.href='index.php'" value="商品一覧"></p>
<p><input type="button" onclick="location.href='NewItemAdd.html'" value="商品新規登録"></p>