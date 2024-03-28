<?php

use App\Models\Drinks;

$drinks = Drinks::query();

if (!empty($keyword)) {
    $drinks->where('syouhinnmei', 'LIKE', '%' . $keyword . '%')
           ->orWhere('gyousya', 'LIKE', '%' . $keyword . '%')
           ->orWhere('kakaku', 'LIKE', '%' . $keyword . '%')
           ->orWhere('zaikosuu', 'LIKE', '%' . $keyword . '%')
           ->orWhere('komennto', 'LIKE', '%' . $keyword . '%');
}

if (!empty($maker)) {
    $drinks->where('gyousya', 'LIKE', '%' . $maker . '%');
}

$drinks = $drinks->get();

?>

<p><input type="button" onclick="location.href='drinks/itiran_viwe.blade.php'" value="商品一覧"></p>