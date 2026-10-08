CREATE DATABASE IF NOT EXISTS shopping_cart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO products (name, price, quantity) VALUES
('Áo sơ mi nam', 250000.00, 50),
('Quần jean nữ', 350000.00, 30),
('Giày thể thao', 600000.00, 15),
('Mũ bảo hiểm', 150000.00, 100),
('Balo du lịch', 450000.00, 20);
