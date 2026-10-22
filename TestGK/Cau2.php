<?php
$productPrices = [150000, 320000, 95000, 450000, 210000];

function calculateTotal($prices) {
    $total = 0;
    foreach ($prices as $price) {
        $total += $price;
    }
    return $total;
}

function findMaxPrice($prices) {
    return max($prices);
}

$tongGiaTri = calculateTotal($productPrices);
$giaCaoNhat = findMaxPrice($productPrices);

echo "Danh sách giá sản phẩm: " . implode(", ", $productPrices) . "<br>";
echo "Tổng giá trị tất cả sản phẩm: " . number_format($tongGiaTri) . " VNĐ<br>";
echo "Giá sản phẩm cao nhất: " . number_format($giaCaoNhat) . " VNĐ";
?>
