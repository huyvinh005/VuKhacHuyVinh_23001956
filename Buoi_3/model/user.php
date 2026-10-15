<?php
require_once __DIR__ . '/../common/dbConnect.php';

// Tìm user theo username
function getUserByUsername($username) {
    global $conn;
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);
    return $stmt->fetch();
}

// Đăng ký tài khoản mới
function registerUser($username, $password) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':username' => $username,
        ':password' => $hashedPassword
    ]);
}
?>
