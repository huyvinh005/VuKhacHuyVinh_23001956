CREATE DATABASE shopping_cart;
USE shopping_cart;

CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO cart_items (name, price, quantity) VALUES
('Bàn phím cơ', 1200000, 3),
('Chuột không dây', 350000, 5),
('Tai nghe Gaming', 850000, 2),
('Lót chuột lớn', 80000, 10),
('Giá đỡ laptop', 150000, 4);

SELECT * FROM cart_items;

SELECT * FROM cart_items 
WHERE price > 100000;

SELECT * FROM cart_items 
WHERE quantity > 5;

SELECT * FROM cart_items 
ORDER BY price DESC;

UPDATE cart_items 
SET price = 90000 
WHERE id = 4;

UPDATE cart_items 
SET quantity = 8 
WHERE id = 2;

DELETE FROM cart_items 
WHERE id = 5;

SELECT name, price, quantity, (price * quantity) AS thanh_tien 
FROM cart_items;

SELECT SUM(price * quantity) AS tong_tien_gio_hang 
FROM cart_items;
