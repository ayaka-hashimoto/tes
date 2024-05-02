<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>在庫管理システム 商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>    
</head>

<body>

<div class="container">
    <h1>商品一覧</h1>

    <form action="{{ route('itiran') }}" method="GET">
    <div class="search-group">
        <input type="text" id="keyword" name="keyword" placeholder="キーワードを検索" value="{{ $keyword ?? '' }}">
    </div>
    <div class="search-group">
        <select class="form-select" id="company_id" name="company_id" placeholder="メーカー名を検索">
            <option value="">--選択してください--</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="search-group">
        <input type="number" id="min_price" name="min_price" placeholder="価格（下限）">
        ～
        <input type="number" id="max_price" name="max_price" placeholder="価格（上限）">円
    </div>
    <div class="search-group">
        <input type="number" id="min_stock" name="min_stock" placeholder="在庫数（下限）">
        ～
        <input type="number" id="max_stock" name="max_stock" placeholder="在庫数（上限）">
    </div>
    <div class="search-group">
        <input type="submit" value="検索">
    </div>
</form>
    
    
    <table id="dataTable" class="table">
        <thead>
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
        </thead>
        <tbody>
        @foreach ($products as $product)
        <tr data-id="{{ $product->id }}">
            <td>{{ $product->id }}</td>
            <td><img src="{{ asset($product->img_path) }}" style='width: 50px; height: 50px;'></td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->price }}円</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company->company_name }}</td>
            <td>{{ $product->comment }}</td>
            <td>
                <form action="{{ route('syousai', $product->id) }}" method="GET">
                    <button type="submit">詳細</button>
                </form>
            </td>
            <td style="text-align:center">
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick='return confirm("削除しますか？")'>削除</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="detailContent"></p>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        let sortOrder = 1; // 初期のソート順序（昇順）

        $('th').click(function() {
            const columnIndex = $(this).index();
            const isNumeric = columnIndex === 0 || columnIndex === 3 || columnIndex === 4; // 数値列かどうか

            $('th').removeClass('asc desc');
            $(this).addClass(sortOrder === 1 ? 'asc' : 'desc');

            const rows = $('tbody tr').get();
            rows.sort(function(rowA, rowB) {
                const cellA = $(rowA).children('td').eq(columnIndex).text().trim();
                const cellB = $(rowB).children('td').eq(columnIndex).text().trim();

                if (isNumeric) {
                    return sortOrder * (parseInt(cellA) - parseInt(cellB));
                } else {
                    return sortOrder * cellA.localeCompare(cellB);
                }
            });

            $.each(rows, function(index, row) {
                $('tbody').append(row);
            });

            sortOrder *= -1; // 昇順と降順を切り替え
        });
    });
</script>

<hr>
<a href="{{ route('NewItemAdd') }}" class="button">新規登録</a>
<a href="{{ route('itiran') }}" class="button">一覧に戻る</a>

</body>
</html>