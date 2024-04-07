<?php

use App\Models\Products;

$products = Products::query();

if (!empty($keyword)) {
    $products->where('product_name', 'LIKE', '%' . $keyword . '%')
           ->orWhere('company_name', 'LIKE', '%' . $keyword . '%')
           ->orWhere('price', 'LIKE', '%' . $keyword . '%')
           ->orWhere('stock', 'LIKE', '%' . $keyword . '%')
           ->orWhere('comment', 'LIKE', '%' . $keyword . '%');
}

if (!empty($maker)) {
    $products->where('company_name', 'LIKE', '%' . $maker . '%');
}

$products = $products->get();

?>

<p><input type="button" onclick="location.href='products/itiran_viwe.blade.php'" value="商品一覧"></p>