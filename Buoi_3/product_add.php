<?php
require_once 'model/product.php';

$errors = [];
$name = '';
$price = '';
$quantity = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    // Validate Tên không được rỗng
    if (empty($name)) {
        $errors[] = 'Tên sản phẩm không được để rỗng.';
    }

    // Validate Giá > 0
    if ($price === '' || !is_numeric($price) || floatval($price) <= 0) {
        $errors[] = 'Giá sản phẩm phải là một số lớn hơn 0.';
    }

    // Validate Số lượng >= 0
    if ($quantity === '' || !is_numeric($quantity) || intval($quantity) < 0) {
        $errors[] = 'Số lượng sản phẩm phải là số nguyên lớn hơn hoặc bằng 0.';
    }

    // Nếu không có lỗi thì thực hiện thêm
    if (empty($errors)) {
        if (addProduct($name, floatval($price), intval($quantity))) {
            header('Location: product_list.php');
            exit();
        } else {
            $errors[] = 'Có lỗi xảy ra khi thêm sản phẩm vào cơ sở dữ liệu.';
        }
    }
}

include 'view/header.php';
?>

<h3>Thêm sản phẩm mới</h3>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="product_add.php" method="POST">
    <div>
        <label>Tên sản phẩm:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
    </div>
    <br>
    <div>
        <label>Giá:</label><br>
        <input type="text" name="price" value="<?php echo htmlspecialchars($price); ?>">
    </div>
    <br>
    <div>
        <label>Số lượng:</label><br>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
    </div>
    <br>
    <button type="submit">Thêm mới</button>
    <a href="product_list.php">Hủy / Quay lại</a>
</form>

<?php include 'view/footer.php'; ?>
