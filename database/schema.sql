-- Create the database
CREATE DATABASE datadash;
USE datadash;

-- User-related tables
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255),
    favorite_movie VARCHAR(20),
    phone VARCHAR(20),
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_type VARCHAR(50) NOT NULL,
    street_address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE payment_methods (
    payment_method_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    method_type VARCHAR(50) NOT NULL,
    card_number VARCHAR(20),
    cvs_number VARCHAR(20),
    expiration_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Product-related tables
CREATE TABLE category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(45) NOT NULL,
    UNIQUE KEY category_name_unique (category_name)
);

CREATE TABLE brands (
    brand_id INT AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(255) NOT NULL
);

CREATE TABLE product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    brand_id INT,
    name VARCHAR(45) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    price DECIMAL(10, 2) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status TINYINT NOT NULL DEFAULT 0,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY name_unique (name),
    KEY idx_product_category_id (category_id),
    CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES category (category_id) ON UPDATE CASCADE,
    CONSTRAINT fk_product_brand FOREIGN KEY (brand_id) REFERENCES brands (brand_id)
);

CREATE TABLE inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    last_updated_date DATETIME NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);

-- Order-related tables
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
CREATE TABLE ordered_item (
  order_id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  product_id int NOT NULL,
  quantity int NOT NULL,
  order_status int NOT NULL DEFAULT '0',
  order_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
);
CREATE TABLE order_details (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE order_history (
    order_history_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    current_status VARCHAR(50) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

CREATE TABLE returns (
    return_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    return_reason TEXT NOT NULL,
    return_date DATETIME NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

-- Shopping cart and wishlist tables
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE cart_product (
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (cart_id, product_id),
    FOREIGN KEY (cart_id) REFERENCES cart(cart_id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(product_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    wishlist_name VARCHAR(255) NOT NULL,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE wishlist_products (
    wishlist_id INT NOT NULL,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    added_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (wishlist_id),
    FOREIGN KEY (wishlist_id) REFERENCES wishlist(wishlist_id),
    FOREIGN KEY (product_id) REFERENCES product(product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    UNIQUE (wishlist_id, product_id) -- Ensure no duplicate product in the same wishlist
);

-- Other tables
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating DECIMAL(2, 1) NOT NULL,
    review_text TEXT,
    review_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE ratings (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating_value DECIMAL(2, 1) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE coupons (
    coupon_id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_code VARCHAR(50) NOT NULL,
    discount_amount DECIMAL(10, 2) NOT NULL,
    expiration_date DATE NOT NULL
);

CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_content TEXT NOT NULL,
    message_date DATETIME NOT NULL,
    FOREIGN KEY (sender_id) REFERENCES users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES users(user_id)
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    notification_content TEXT NOT NULL,
    notification_date DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE shipping_methods (
    shipping_method_id INT AUTO_INCREMENT PRIMARY KEY,
    method_name VARCHAR(100) NOT NULL,
    estimated_delivery_time INT NOT NULL
);

CREATE TABLE taxes (
    tax_id INT AUTO_INCREMENT PRIMARY KEY,
    country VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    tax_rate DECIMAL(5, 2) NOT NULL
);

CREATE TABLE discounts (
    discount_id INT AUTO_INCREMENT PRIMARY KEY,
    discount_name VARCHAR(100) NOT NULL,
    discount_type VARCHAR(50) NOT NULL,
    discount_value DECIMAL(10, 2) NOT NULL
);

CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(255) NOT NULL,
    contact_name VARCHAR(255) NOT NULL,
    contact_email VARCHAR(255) NOT NULL,
    contact_phone VARCHAR(20) NOT NULL
);

CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    transaction_date DATETIME NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method_id INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(payment_method_id)
);

CREATE TABLE analytics (
    analytics_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    page_visited VARCHAR(255) NOT NULL,
    timestamp DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subscription_type VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE recommendations (
    user_id INT NOT NULL,
    recommended_product_id INT NOT NULL,
    recommendation_score DECIMAL(5, 2) NOT NULL,
    PRIMARY KEY (user_id, recommended_product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (recommended_product_id) REFERENCES product(product_id)
);

CREATE TABLE custom_fields (
    custom_field_id INT AUTO_INCREMENT PRIMARY KEY,
    field_name VARCHAR(255) NOT NULL,
    field_type VARCHAR(50) NOT NULL,
    field_value TEXT NOT NULL,
    product_id INT, -- Example: associate with product table
    FOREIGN KEY (product_id) REFERENCES product(product_id)
);

-- Indexes
CREATE INDEX idx_orders_user_id ON orders (user_id);
CREATE INDEX idx_order_details_order_id ON order_details (order_id);
CREATE INDEX idx_order_details_product_id ON order_details (product_id);
CREATE INDEX idx_order_history_order_id ON order_history (order_id);
CREATE INDEX idx_order_history_user_id ON order_history (user_id);
CREATE INDEX idx_returns_order_id ON returns (order_id);
CREATE INDEX idx_cart_user_id ON cart (user_id);
CREATE INDEX idx_cart_product_product_id ON cart_product (product_id);
CREATE INDEX idx_wishlist_user_id ON wishlist (user_id);
CREATE INDEX idx_wishlist_product_id ON wishlist_products (product_id);
CREATE INDEX idx_reviews_user_id ON reviews (user_id);
CREATE INDEX idx_reviews_product_id ON reviews (product_id);
CREATE INDEX idx_ratings_user_id ON ratings (user_id);
CREATE INDEX idx_ratings_product_id ON ratings (product_id);
CREATE INDEX idx_messages_sender_id ON messages (sender_id);
CREATE INDEX idx_messages_receiver_id ON messages (receiver_id);
CREATE INDEX idx_notifications_user_id ON notifications (user_id);
CREATE INDEX idx_transactions_order_id ON transactions (order_id);
CREATE INDEX idx_transactions_payment_method_id ON transactions (payment_method_id);
CREATE INDEX idx_analytics_user_id ON analytics (user_id);
CREATE INDEX idx_subscriptions_user_id ON subscriptions (user_id);
CREATE INDEX idx_recommendations_user_id ON recommendations (user_id);
CREATE INDEX idx_recommendations_product_id ON recommendations (recommended_product_id);

-- Triggers
DELIMITER //
CREATE TRIGGER create_cart_after_user_registration AFTER INSERT ON users
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM cart WHERE user_id = NEW.user_id) THEN
        INSERT INTO cart (user_id) VALUES (NEW.user_id);
    END IF;
END//
CREATE TRIGGER create_order_history_after_first_order AFTER INSERT ON orders
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM order_history WHERE user_id = NEW.user_id) THEN
        INSERT INTO order_history (order_id, user_id, order_date, total_amount, status, current_status)
        VALUES (NEW.order_id, NEW.user_id, NEW.order_date, NEW.total_amount, 'new', 'new');
    END IF;
END//
CREATE TRIGGER update_order_status AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    UPDATE orders SET status = 'processing' WHERE order_id = NEW.order_id;
END//
DELIMITER ;

CREATE TABLE sessions (
    session_id varchar(33) PRIMARY KEY,
    user_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);