<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <title>在庫管理システム　商品一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>商品一覧</h1>
<form action="search.php" method="get">
    <label for="keyword">キーワード検索:</label>
    <input type="text" id="keyword" name="keyword">
    <label for="maker">メーカー名で絞り込み:</label>
    <input type="text" id="maker" name="maker">
    <input type="submit" value="検索">
</form>
<?php
    include 'itirann.php';

    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $maker = isset($_GET['maker']) ? $_GET['maker'] : '';

    $whereClause = [];
    $params = [];

    if (!empty($keyword)) {
        $whereClause[] = "syouhinnmei LIKE :keyword";
        $params[':keyword'] = '%' . $keyword . '%';
    }

    if (!empty($maker)) {
        $whereClause[] = "gyousya LIKE :maker";
        $params[':maker'] = '%' . $maker . '%';
    }

    $where = !empty($whereClause) ? " WHERE " . implode(' AND ', $whereClause) : "";
    $sql = "SELECT * FROM drinks" . $where;
    $stmt = $dbh->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->execute();
?>

<!-- テーブルの開始 -->
<table border='1'>
    <tr>
        <th>ID</th>
        <th>商品画像</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫数</th>
        <th>メーカー名</th>
        <th>コメント</th>
        <th>商品情報詳細</th>
        <th>商品削除</th>
    </tr>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr data-id="<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">
            <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><img src="./syasinn/<?= htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8') ?>"' style='width: 50px; height: 50px;'></td>
            <td><?= htmlspecialchars($row['syouhinnmei'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['kakaku'], ENT_QUOTES, 'UTF-8') ?>円</td>
            <td><?= htmlspecialchars($row['zaikosuu'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['gyousya'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['komennto'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><button class='detail-btn' data-id='<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'>詳細</button></td>
            <td><button class='hide-btn' data-id='<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>'>削除</button></td>
        </tr>
    <?php endwhile; ?>
</table>


<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="detailContent"></p>
    </div>
</div>

<!-- スクリプトタグの配置 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.detail-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var id = e.target.getAttribute('data-id');
            window.location.href = '/tes/drinks/ts/syousai.php?id=' + id;
            fetch(`get-detail.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('detailContent').textContent = data.detail;
                    document.getElementById('detailModal').style.display = 'block';

                    document.querySelector('.close').addEventListener('click', function() {
                        document.getElementById('detailModal').style.display = 'none';
                    });
                });
        });
    });

    document.querySelectorAll('.hide-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var id = e.target.getAttribute('data-id');
            alert('商品ID: ' + id + ' の詳細情報を非表示にします。');
            var targetRow = document.querySelector('tr[data-id="' + id + '"]');
            if (targetRow) {
                targetRow.style.display = 'none';
            }
        });
    });
});
</script>

<hr>
<p><input type="button" onclick="location.href='NewItemAdd.html'" value="商品新規登録"></p>

</body>
</html>