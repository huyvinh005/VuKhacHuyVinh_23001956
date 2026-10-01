CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

INSERT INTO movies (title, price, total_seats, available_seats) VALUES
('Avengers: Endgame', 95000, 120, 40),
('Mat Biec', 75000, 100, 10),
('Lat Mat 7', 90000, 150, 60),
('Mai', 110000, 130, 25),
('Nha Ba Nu', 85000, 110, 55);

SELECT * FROM movies;

SELECT * FROM movies 
WHERE price > 100000;

SELECT * FROM movies 
WHERE available_seats > 50;

SELECT * FROM movies 
ORDER BY price DESC;

UPDATE movies 
SET available_seats = 35 
WHERE id = 1;

DELETE FROM movies 
WHERE id = 2;

SELECT title, (total_seats - available_seats) AS ve_da_ban 
FROM movies;

SELECT title, ((total_seats - available_seats) * price) AS doanh_thu 
FROM movies;

SELECT SUM((total_seats - available_seats) * price) AS tong_doanh_thu 
FROM movies;

SELECT title, (total_seats - available_seats) AS ve_da_ban 
FROM movies 
ORDER BY ve_da_ban DESC 
LIMIT 1;
