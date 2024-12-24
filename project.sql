-- -- Create the database
-- CREATE DATABASE shoeshub;

-- -- Use the database
-- USE shoeshub;

-- Table: Users (Regular Customers)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Admins (Admin-specific details)
CREATE TABLE admins (
   admin_id INT AUTO_INCREMENT PRIMARY KEY,
    ad_name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Categories (Product categories)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Products (Footwear details)
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(70) NOT NULL,
    name VARCHAR(255) NOT NULL,
    type varchar(200) NOT NULL,
    short_desc varchar(250),
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    category_id INT,
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255),
    image4 VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table: Orders (Customer orders)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Table: Order Items (Products in orders)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);


-- Table: Cart (User cart for checkout)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Table: Payment (Payment records)
CREATE TABLE payment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('cash_on_delivery', 'esewa', 'khalti') NOT NULL,
    status ENUM('cod', 'pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    CONSTRAINT chk_status_method CHECK (
        (payment_method = 'cash_on_delivery' AND status = 'cod') OR
        (payment_method IN ('esewa', 'khalti', 'card') AND status IN ('pending', 'completed', 'failed'))
    )
);

-- Table: Contacts (User messages to admin)
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, -- Nullable for guest users
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,

    message TEXT NOT NULL,
    status ENUM('pending', 'read', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);


ALTER TABLE `admins` ADD `Shop_Name` VARCHAR(26) NOT NULL AFTER `phone`, 
ADD `Shop_Logo` VARCHAR(255) NOT NULL AFTER `Shop_Name`, 
ADD `Shop_Address` VARCHAR(200) NOT NULL AFTER `Shop_Logo`;


ALTER TABLE `admins` ADD `About_shop` VARCHAR(300) NULL DEFAULT NULL AFTER `Shop_Address`;
-- Table: Reviews (Customer feedback)
-- CREATE TABLE reviews (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     product_id INT NOT NULL,
--     rating INT CHECK (rating BETWEEN 1 AND 5),
--     comment TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
--     FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
-- );