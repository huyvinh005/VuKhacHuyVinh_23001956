<?php
require_once 'model/product.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: product_list.php');
    exit();
}

$product = getProductById($id);

if (!$product) {
    include 'view/header.php';
    echo "<p style='color: red;'>Sản phẩm không tồn tại!</p>";
    echo "<a href='product_list.php'>Quay lại danh sách</a>";
    include 'view/footer.php';
    exit();
}

$errors = [];
$name = $product['name'];
$price = $product['price'];
$quantity = $product['quantity'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    // Validate dữ liệu
    if (empty($name)) {
        $errors[] = 'Tên sản phẩm không được để rỗng.';
    }

    if ($price === '' || !is_numeric($price) || floatval($price) <= 0) {
        $errors[] = 'Giá sản phẩm phải là một số lớn hơn 0.';
    }

    if ($quantity === '' || !is_numeric($quantity) || intval($quantity) < 0) {
        $errors[] = 'Số lượng sản phẩm phải là số nguyên lớn hơn hoặc bằng 0.';
    }

    // Nếu không có lỗi thì thực hiện cập nhật
    if (empty($errors)) {
        if (updateProduct($id, $name, floatval($price), intval($quantity))) {
            header('Location: product_list.php');
            exit();
        } else {
            $errors[] = 'Có lỗi xảy ra khi cập nhật thông tin sản phẩm.';
        }
    }
}

include 'view/header.php';
?>

<h3>Chỉnh sửa sản phẩm</h3>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="product_edit.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
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
    <button type="submit">Cập nhật</button>
    <a href="product_list.php">Hủy / Quay lại</a>
</form>

<?php include 'view/footer.php'; ?>
