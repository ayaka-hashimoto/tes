<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>在庫管理システム　商品一覧</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
</body>
</html>

<?php
// itirann.php ファイルを含めて、データベースへの接続
include 'itirann.php';

// フォームから送信されたキーワードとメーカー名取得。
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$maker = isset($_GET['maker']) ? $_GET['maker'] : '';

// 初期の検索クエリ
$sql = "SELECT * FROM drinks";
$stmt = $dbh->prepare($sql);
$stmt->execute();

// 条件を組み立てる
$whereClause = [];
$params = [];

if (!empty($keyword)) {
    $whereClause[] = "(syouhinnmei LIKE :keyword OR gyousya LIKE :keyword OR kakaku LIKE :keyword OR zaikosuu LIKE :keyword OR komennto LIKE :keyword)";
    $params[':keyword'] = '%' . $keyword . '%';
}

if (!empty($maker)) {
    $whereClause[] = "gyousya LIKE :maker";
    $params[':maker'] = '%' . $maker . '%';
}

if (!empty($whereClause)) {
    $where = " WHERE " . implode(' AND ', $whereClause);
    $sql = "SELECT * FROM drinks" . $where;
    $stmt = $dbh->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->execute();
}

echo "<table border='1'>";
echo "<tr><th>ID</th><th>商品画像</th><th>商品名</th><th>価格</th><th>在庫数</th><th>メーカー名</th><th>コメント</th></tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td><img src='" . htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8') . "' style='width: 50px; height: 50px;'></td>";
    echo "<td>" . htmlspecialchars($row['syouhinnmei'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['kakaku'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['zaikosuu'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['gyousya'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['komennto'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "</tr>";
}

echo "</table>";
?>

<p><input type="button" onclick="location.href='index.php'" value="商品一覧"></p>