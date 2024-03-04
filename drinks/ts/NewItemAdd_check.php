<?php
error_reporting(-1);

/* データベース設定 */
define('DB_DNS', 'mysql:host=localhost;dbname=tes;charset=utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

/* データベースへ接続 */
try {
    $dbh = new PDO(DB_DNS, DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e){
    echo $e->getMessage();
    exit;
}

/* データベースへ登録 */
try {
    if(!empty($_POST['inputName'])){
        $sql = 'INSERT INTO drinks(syouhinnmei, kakaku, zaikosuu, gyousya, komennto) VALUES(:syouhinnmei, :kakaku, :zaikosuu, :gyousya, :komennto)';
        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':syouhinnmei', $_POST['inputName'], PDO::PARAM_STR);
        $stmt->bindValue(':kakaku', $_POST['kakaku'], PDO::PARAM_INT);
        $stmt->bindValue(':zaikosuu', $_POST['zaikosuu'], PDO::PARAM_INT);
        $stmt->bindValue(':gyousya', $_POST['gyousya'], PDO::PARAM_STR);
        $stmt->bindValue(':komennto', $_POST['komennto'], PDO::PARAM_STR);
        $stmt->execute();

        // ファイルアップロードの処理
        if(isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == UPLOAD_ERR_OK){
            $file_name = $_FILES['upload_file']['name'];
            $file_temp = $_FILES['upload_file']['tmp_name'];
            $file_size = $_FILES['upload_file']['size'];
            $file_type = $_FILES['upload_file']['type'];
            $date_uploaded = date("Y-m-d");
            $location = "drinks/".$file_name; 

            if($file_size < 5242880){ 
                if(move_uploaded_file($file_temp, $location)){
                    $sql = 'INSERT INTO drinks(syouhinnmei, kakaku, zaikosuu, gyousya, komennto, image_path) VALUES(:syouhinnmei, :kakaku, :zaikosuu, :gyousya, :komennto, :image_path)';
                    $stmt->closeCursor(); 
                    $stmt = $dbh->prepare('INSERT INTO drinks(syouhinnmei, kakaku, zaikosuu, gyousya, komennto, image_path) VALUES(:syouhinnmei, :kakaku, :zaikosuu, :gyousya, :komennto, :image_path)');
                    $stmt->bindValue(':image_path', $location, PDO::PARAM_STR); // $location を使用
                    $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
                    $stmt->bindValue(':file_name', $file_name, PDO::PARAM_STR);
                    $stmt->bindValue(':file_type', $file_type, PDO::PARAM_STR);
                    $stmt->bindValue(':date_uploaded', $date_uploaded, PDO::PARAM_STR);
                    $stmt->bindValue(':location', $location, PDO::PARAM_STR);
                    $stmt->execute();
                } else {
                    echo "<center><h3 class='text-danger'>Failed to move uploaded file!</h3></center>";
                }
            } else {
                echo "<center><h3 class='text-danger'>File too large to upload!</h3></center>";
            }

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

if (!in_array($file_extension, $allowed_extensions)) {
    echo "<center><h3 class='text-danger'>Invalid file type!</h3></center>";
    exit();
}
        }

        header('Location: http://localhost/tes/drinks/ts/NewItemAdd.html');
        exit();
    } else {
        /* 新規在庫登録画面を表示する */
        header('Location: http://localhost/tes/drinks/ts/NewItemAdd.html');
        exit();
    }
} catch (PDOException $e) {
    echo 'データベースにアクセスできません！'.$e->getMessage();
} catch (Exception $e) {
    /* ログイン画面を表示する */
    header('Location: index.html');
    exit();
}
?>
