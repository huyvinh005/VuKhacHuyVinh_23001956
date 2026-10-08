<?php
require_once 'model/product.php';

$id = $_GET['id'] ?? null;

include 'view/header.php';

if (!$id) {
    echo "<p style='color: red;'>ID sản phẩm không hợp lệ!</p>";
    echo "<a href='product_list.php'>Quay lại danh sách</a>";
    include 'view/footer.php';
    exit();
}

$product = getProductById($id);

if (!$product) {
    echo "<p style='color: red;'>Sản phẩm không tồn tại hoặc đã bị xóa trước đó!</p>";
    echo "<a href='product_list.php'>Quay lại danh sách</a>";
    include 'view/footer.php';
    exit();
}

// Tiến hành xóa sản phẩm
if (deleteProduct($id)) {
    header('Location: product_list.php');
    exit();
} else {
    echo "<p style='color: red;'>Đã xảy ra lỗi trong quá trình xóa sản phẩm!</p>";
    echo "<a href='product_list.php'>Quay lại danh sách</a>";
}

include 'view/footer.php';
?>
