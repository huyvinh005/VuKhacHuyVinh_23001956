<?php
require_once __DIR__ . '/../common/dbConnect.php';

function getAllProducts() {
    global $conn;
    $sql = "SELECT * FROM products ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProductById($id) {
    global $conn;
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function addProduct($name, $price, $quantity) {
    global $conn;
    $sql = "INSERT INTO products (name, price, quantity) VALUES (:name, :price, :quantity)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':quantity' => $quantity
    ]);
}

function updateProduct($id, $name, $price, $quantity) {
    global $conn;
    $sql = "UPDATE products SET name = :name, price = :price, quantity = :quantity WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':price' => $price,
        ':quantity' => $quantity
    ]);
}

function deleteProduct($id) {
    global $conn;
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
?>
