<?php
session_start();
require_once 'model/user.php';

if (isset($_SESSION['user'])) {
    header('Location: product_list.php');
    exit();
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $errors[] = 'Tên đăng nhập và mật khẩu không được để trống.';
    } else {
        if (getUserByUsername($username)) {
            $errors[] = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
        }
    }

    if (empty($errors)) {
        if (registerUser($username, $password)) {
            header('Location: login.php?registered=1');
            exit();
        } else {
            $errors[] = 'Có lỗi xảy ra trong quá trình đăng ký.';
        }
    }
}

include 'view/header.php';
?>

<h3>Đăng ký tài khoản</h3>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="register.php" method="POST">
    <div>
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
    </div>
    <br>
    <div>
        <label>Mật khẩu:</label><br>
        <input type="password" name="password">
    </div>
    <br>
    <button type="submit">Đăng ký</button>
    <a href="login.php">Đã có tài khoản? Đăng nhập</a>
</form>

<?php include 'view/footer.php'; ?>
