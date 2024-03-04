<?php
include 'itirann.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $syouhinnmei = $_POST['syouhinnmei'];
    $kakaku = $_POST['kakaku'];
    $zaikosuu = $_POST['zaikosuu'];
    $gyousya = $_POST['gyousya'];
    $komennto = $_POST['komennto'];

    $uploadDir = './';
    $extensions = array("jpeg", "jpg", "png");

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $file_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!in_array($file_ext, $extensions)) {
            echo "extension not allowed, please choose a JPEG or PNG file.";
            exit;
        }

        $imagePath = $uploadDir . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $absoluteImagePath = $imagePath;
        } else {
            echo "画像のアップロードに失敗しました。";
            exit;
        }
    } else {
        $absoluteImagePath = ''; 
    }

    $sql = "INSERT INTO drinks (syouhinnmei, kakaku, zaikosuu, gyousya, komennto, image_path) VALUES (:syouhinnmei, :kakaku, :zaikosuu, :gyousya, :komennto, :image_path)";
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':syouhinnmei', $syouhinnmei);
    $stmt->bindParam(':kakaku', $kakaku);
    $stmt->bindParam(':zaikosuu', $zaikosuu);
    $stmt->bindParam(':gyousya', $gyousya);
    $stmt->bindParam(':komennto', $komennto);
    $stmt->bindParam(':image_path', $absoluteImagePath);

    if ($stmt->execute()) {
        header('Location: http://localhost/tes/drinks/ts/NewItemAdd.html');
        exit; 
    } else {
        echo "データベースにデータを挿入できませんでした。";
    }
}