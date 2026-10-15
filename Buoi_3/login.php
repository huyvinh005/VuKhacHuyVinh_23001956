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
        $errors[] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        $user = getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username']
            ];
            header('Location: product_list.php');
            exit();
        } else {
            $errors[] = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
        }
    }
}

include 'view/header.php';
?>

<h3>Đăng nhập hệ thống</h3>

<?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
    <p style="color: green;">Đăng ký thành công! Vui lòng đăng nhập.</p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="login.php" method="POST">
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
    <button type="submit">Đăng nhập</button>
    <a href="register.php">Chưa có tài khoản? Đăng ký ngay</a>
</form>

<?php include 'view/footer.php'; ?>
