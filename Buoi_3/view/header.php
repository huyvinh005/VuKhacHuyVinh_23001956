<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý giỏ hàng</title>
</head>
<body>
    <header>
        <h2>HỆ THỐNG QUẢN LÝ SẢN PHẨM GIỎ HÀNG</h2>
        <hr>
        <?php if (isset($_SESSION['user'])): ?>
            <p>
                Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong> | 
                <a href="logout.php">Đăng xuất</a>
            </p>
        <?php endif; ?>
    </header>
    <main>
