-- Create database and use it
CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	password VARCHAR(255) NOT NULL,
	phone VARCHAR(20),
	address TEXT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(200) NOT NULL,
	description TEXT,
	price DECIMAL(10,2) NOT NULL,
	image VARCHAR(255),
	stock_quantity INT DEFAULT 0,
	category VARCHAR(100),
	rating DECIMAL(2,1) DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
	id INT PRIMARY KEY AUTO_INCREMENT,
	user_id INT,
	product_id INT,
	quantity INT DEFAULT 1,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
	id INT PRIMARY KEY AUTO_INCREMENT,
	user_id INT,
	total_amount DECIMAL(10,2),
	payment_status VARCHAR(50) DEFAULT 'pending',
	order_status VARCHAR(50) DEFAULT 'processing',
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
	id INT PRIMARY KEY AUTO_INCREMENT,
	order_id INT,
	product_id INT,
	quantity INT,
	price DECIMAL(10,2),
	FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Sample products
INSERT INTO products (name, description, price, image, stock_quantity, category, rating) VALUES
('Laptop Pro', 'High-performance laptop for professionals', 1299.99, 'laptop.jpg', 50, 'Electronics', 4.5),
('Smartphone X', 'Latest smartphone with advanced features', 899.99, 'phone.jpg', 100, 'Electronics', 4.2),
('Wireless Headphones', 'Premium wireless headphones with noise cancellation', 299.99, 'headphones.jpg', 75, 'Electronics', 4.7),
('Gaming Mouse', 'Professional gaming mouse with RGB lighting', 79.99, 'mouse.jpg', 200, 'Accessories', 4.3),
('Mechanical Keyboard', 'RGB mechanical keyboard for gaming', 159.99, 'keyboard.jpg', 150, 'Accessories', 4.6); 