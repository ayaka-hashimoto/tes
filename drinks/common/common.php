<?php
// データベース接続設定
define('DB_HOST', 'localhost');
define('DB_NAME', 'tes');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// エラーメッセージを格納する変数
$ERROR = array();

try {
    // PDOを使用してデータベースに接続
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);

    // フォームから送信されたデータを取得
    $syouhinnmei = $_POST['syouhinnmei'];
    $kakaku = $_POST['kakaku'];
    $zaikosuu = $_POST['zaikosuu'];
    $gyousya = $_POST['gyousya'];

    // 画像ファイルのアップロード処理
    $upload_file = $_FILES['upload_file'];
    $upload_dir = 'uploads/'; // アップロード先のディレクトリ
    $upload_path = $upload_dir . basename($upload_file['name']);

    // ファイルのアップロード
    if (move_uploaded_file($upload_file['tmp_name'], $upload_path)) {
        echo "ファイルのアップロードに成功しました。";
    } else {
        echo "ファイルのアップロードに失敗しました。";
    }

    // データベースにデータを挿入するSQLクエリ
    $sql = "INSERT INTO your_table_name (syouhinnmei, kakaku, zaikosuu, gyousya, image_path) VALUES (:syouhinnmei, :kakaku, :zaikosuu, :gyousya, :image_path)";

    // SQLクエリを準備
    $sth = $db->prepare($sql);

    // パラメータをバインド
    $sth->bindParam(':syouhinnmei', $syouhinnmei);
    $sth->bindParam(':kakaku', $kakaku);
    $sth->bindParam(':zaikosuu', $zaikosuu);
    $sth->bindParam(':gyousya', $gyousya);
    $sth->bindParam(':image_path', $upload_path);

    // SQLクエリを実行
    $sth->execute();

    echo "新商品の登録が完了しました。";
} catch (PDOException $e) {
    // 接続エラーを処理
    $ERROR[] = $e->getMessage();
}
?>