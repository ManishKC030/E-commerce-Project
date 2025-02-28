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

-- Table: Admins (Admin-specific details) // Admin_Id also means Shop ID
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
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- adding billing address---
ALTER TABLE orders 
ADD billing_name VARCHAR(100) NOT NULL,
ADD billing_phone VARCHAR(15) NOT NULL,
ADD billing_email VARCHAR(100) NOT NULL,
ADD billing_country VARCHAR(100) NOT NULL DEFAULT 'Nepal',
ADD billing_city VARCHAR(100) NOT NULL,
ADD billing_street VARCHAR(100) NOT NULL,
ADD billing_zip VARCHAR(50) NOT NULL;


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
    payment_method ENUM('cash_on_delivery', 'stripe') NOT NULL,
    status ENUM('cod', 'pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
   
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    CONSTRAINT chk_status_method CHECK (
        (payment_method = 'cash_on_delivery' AND status = 'cod') OR
        (payment_method IN ('stripe') AND status IN ('pending', 'completed', 'failed'))
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
ADD `Shop_Address` VARCHAR(200) NOT NULL AFTER `Shop_Logo`,
ADD `About_shop` VARCHAR(300) NULL DEFAULT NULL AFTER `Shop_Address`;




-- Add the shop_id column to the products table
ALTER TABLE products ADD admin_id INT NOT NULL;

-- Add the foreign key constraint linking products.shop_id to admins.shop_id

ALTER TABLE `products` ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`admin_id`) ON DELETE CASCADE;


-- Add admin_id to the orders table
ALTER TABLE orders ADD admin_id INT NOT NULL;

-- Add the foreign key constraint linking orders.admin_id to admins.admin_id
ALTER TABLE orders
ADD CONSTRAINT fk_orders_admin_id
FOREIGN KEY (admin_id) REFERENCES admins(admin_id) ON DELETE CASCADE;

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



------this is clean code but check before use----

-- -- Table: Users
-- CREATE TABLE users (
--     user_id INT AUTO_INCREMENT PRIMARY KEY,
--     username VARCHAR(100) NOT NULL,
--     email VARCHAR(100) NOT NULL UNIQUE,
--     password VARCHAR(255) NOT NULL,
--     phone VARCHAR(15),
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Table: Admins (Shops)
-- CREATE TABLE admins (
--     admin_id INT AUTO_INCREMENT PRIMARY KEY,  -- Also represents Shop ID
--     ad_name VARCHAR(100) NOT NULL,
--     email VARCHAR(100) NOT NULL UNIQUE,
--     password VARCHAR(255) NOT NULL,
--     phone VARCHAR(15),
--     Shop_Name VARCHAR(26) NOT NULL,
--     Shop_Logo VARCHAR(255) NOT NULL,
--     Shop_Address VARCHAR(200) NOT NULL,
--     About_shop VARCHAR(300) NULL DEFAULT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Table: Categories
-- CREATE TABLE categories (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL UNIQUE,
--     description TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- Table: Products
-- CREATE TABLE products (
--     product_id INT AUTO_INCREMENT PRIMARY KEY,
--     brand VARCHAR(70) NOT NULL,
--     name VARCHAR(255) NOT NULL,
--     type VARCHAR(200) NOT NULL,
--     short_desc VARCHAR(250),
--     description TEXT,
--     price DECIMAL(10, 2) NOT NULL,
--     stock INT NOT NULL,
--     category_id INT,
--     admin_id INT NOT NULL,  -- Added shop reference
--     image1 VARCHAR(255),
--     image2 VARCHAR(255),
--     image3 VARCHAR(255),
--     image4 VARCHAR(255),
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
--     FOREIGN KEY (admin_id) REFERENCES admins(admin_id) ON DELETE CASCADE
-- );

-- -- Table: Orders
-- CREATE TABLE orders (
--     order_id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     admin_id INT NOT NULL,  -- Added shop reference
--     product_id INT NOT NULL,
--     quantity INT NOT NULL,
--     price DECIMAL(10, 2) NOT NULL,
--     total_price DECIMAL(10, 2) NOT NULL,
--     status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
--     FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
--     FOREIGN KEY (admin_id) REFERENCES admins(admin_id) ON DELETE CASCADE
-- );

-- -- Table: Cart
-- CREATE TABLE cart (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     product_id INT NOT NULL,
--     quantity INT NOT NULL,
--     FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
--     FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
-- );

-- -- Table: Payment
-- CREATE TABLE payment (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     order_id INT NOT NULL,
--     payment_method ENUM('cash_on_delivery', 'stripe') NOT NULL,
--     status ENUM('cod', 'pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
--     CONSTRAINT chk_status_method CHECK (
--         (payment_method = 'cash_on_delivery' AND status = 'cod') OR
--         (payment_method = 'stripe' AND status IN ('pending', 'completed', 'failed'))
--     )
-- );

-- -- Table: Contacts
-- CREATE TABLE contacts (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NULL, -- Nullable for guest users
--     name VARCHAR(100) NOT NULL,
--     email VARCHAR(100) NOT NULL,
--     message TEXT NOT NULL,
--     status ENUM('pending', 'read', 'resolved') DEFAULT 'pending',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
-- );
