-- Câu 1: Cơ sở dữ liệu và ba sản phẩm mẫu theo đề bài.
CREATE DATABASE IF NOT EXISTS buoi_8
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE buoi_8;

CREATE TABLE IF NOT EXISTS products (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    CONSTRAINT chk_product_name CHECK (CHAR_LENGTH(TRIM(name)) BETWEEN 1 AND 100),
    CONSTRAINT chk_product_price CHECK (price > 0),
    CONSTRAINT chk_product_stock CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, price, stock) VALUES
    ('Bút bi', 5000, 20),
    ('Vở ô ly', 12000, 15),
    ('Thước kẻ', 8000, 0);
