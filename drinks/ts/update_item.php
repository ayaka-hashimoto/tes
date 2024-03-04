<?php
include 'itirann.php';

// PDOのエラーモードを設定
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// フォームから送信されたデータを取得
$id = isset($_POST['id']) ? $_POST['id'] : '';
$image_path = isset($_POST['image_path']) ? $_POST['image_path'] : '';
$syouhinnmei = isset($_POST['syouhinnmei']) ? $_POST['syouhinnmei'] : '';
$kakaku = isset($_POST['kakaku']) ? $_POST['kakaku'] : '';
$zaikosuu = isset($_POST['zaikosuu']) ? $_POST['zaikosuu'] : '';
$gyousya = isset($_POST['gyousya']) ? $_POST['gyousya'] : '';
$komennto = isset($_POST['komennto']) ? $_POST['komennto'] : '';

// 更新処理
$sql = "UPDATE drinks SET image_path = :image_path, syouhinnmei = :syouhinnmei, kakaku = :kakaku, zaikosuu = :zaikosuu, gyousya = :gyousya, komennto = :komennto WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
$stmt->bindParam(':syouhinnmei', $syouhinnmei, PDO::PARAM_STR);
$stmt->bindParam(':kakaku', $kakaku, PDO::PARAM_STR);
$stmt->bindParam(':zaikosuu', $zaikosuu, PDO::PARAM_INT);
$stmt->bindParam(':gyousya', $gyousya, PDO::PARAM_STR);
$stmt->bindParam(':komennto', $komennto, PDO::PARAM_STR);

// 更新処理を実行し、結果を$resultに代入
$result = $stmt->execute();

if ($result) {
    // 更新が成功したら、syousai.phpにリダイレクトし、更新した商品のIDをGETパラメータとして渡す
    header("Location: syousai.php?id=$id");
} else {
    echo "更新に失敗しました。";
}
?>