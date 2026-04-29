CREATE DATABASE IF NOT EXISTS clothing_shop;
USE clothing_shop;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (name) VALUES
('Гутал'), ('Цамц'), ('Өмд');

INSERT INTO products (category_id, name, price, image, description) VALUES
(1,'Nike Air Max',250000,'shoe1.jpg','Sport gutal'),
(1,'Adidas Runner',220000,'shoe2.jpg','Running gutal'),
(1,'Puma Classic',180000,'shoe3.jpg','Classic gutal'),
(1,'Converse Black',160000,'shoe4.jpg','Casual gutal'),
(1,'Vans Old Skool',170000,'shoe5.jpg','Street style'),
(1,'New Balance 574',240000,'shoe6.jpg','Comfort gutal'),
(1,'Reebok Club',190000,'shoe7.jpg','Daily wear'),
(1,'Jordan Retro',450000,'shoe8.jpg','Basketball style'),
(1,'Asics Gel',230000,'shoe9.jpg','Running'),
(1,'Timberland Boot',390000,'shoe10.jpg','Boot'),

(2,'Black T-Shirt',45000,'shirt1.jpg','Har tsamts'),
(2,'White T-Shirt',45000,'shirt2.jpg','Tsagaan tsamts'),
(2,'Oversize Hoodie',120000,'shirt3.jpg','Hoodie'),
(2,'Formal Shirt',85000,'shirt4.jpg','Alban yosnii tsamts'),
(2,'Polo Shirt',65000,'shirt5.jpg','Polo'),

(3,'Blue Jeans',95000,'pants1.jpg','Jeans'),
(3,'Black Pants',85000,'pants2.jpg','Classic umd'),
(3,'Sport Pants',75000,'pants3.jpg','Sport umd');
