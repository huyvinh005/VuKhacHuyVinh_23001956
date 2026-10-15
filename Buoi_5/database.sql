CREATE DATABASE IF NOT EXISTS shopping_cart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO products (name, price, quantity) VALUES
('Sản phẩm A', 150000.00, 10),
('Sản phẩm B', 250000.00, 5),
('Sản phẩm C', 50000.00, 20),
('Sản phẩm D', 120000.00, 0),
('Sản phẩm E', 300000.00, 8);

INSERT INTO users (username, password) VALUES
('admin', '$2y$10$44.HhO2uH65sI6.hXG1j7.8P5sV3M3yH9k3Y7Y0Y.Y2Y0Y1Y2Y0Y2');
