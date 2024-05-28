-- Create the database
CREATE DATABASE datadash;
USE datadash;

-- User-related tables
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(255) NOT NULL, -- User's first name
  last_name VARCHAR(255) NOT NULL, -- User's last name
  username VARCHAR(255) NOT NULL, -- User's username
  email VARCHAR(255) NOT NULL, -- User's email address
  password_hash VARCHAR(255), -- Hashed password
  phone VARCHAR(20), -- User's phone number
  registration_date DATETIME DEFAULT CURRENT_TIMESTAMP -- User's registration date
);

CREATE TABLE addresses (
  address_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User associated with the address
  address_type VARCHAR(50) NOT NULL, -- Type of address (e.g., billing, shipping)
  street_address VARCHAR(255) NOT NULL, -- Street address
  city VARCHAR(100) NOT NULL, -- City
  state VARCHAR(100) NOT NULL, -- State or province
  postal_code VARCHAR(20) NOT NULL, -- Postal code
  country VARCHAR(100) NOT NULL, -- Country
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE payment_methods (
  payment_method_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User associated with the payment method
  method_type VARCHAR(50) NOT NULL, -- Type of payment method (e.g., credit card, PayPal)
  card_number VARCHAR(20), -- Credit card number
  expiration_date DATE, -- Credit card expiration date
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Product-related tables
CREATE TABLE category (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  status TINYINT(1) NOT NULL DEFAULT 0, -- Category status (0 = inactive, 1 = active)
  name VARCHAR(45) NOT NULL, -- Category name
  UNIQUE KEY name_unique (name) -- Unique constraint for category name
);

CREATE TABLE brands (
  brand_id INT AUTO_INCREMENT PRIMARY KEY,
  brand_name VARCHAR(255) NOT NULL -- Brand name
);

CREATE TABLE product (
  product_id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL, -- Category associated with the product
  brand_id INT, -- Brand associated with the product
  name VARCHAR(45) NOT NULL, -- Product name
  description VARCHAR(255) DEFAULT NULL, -- Product description
  price DECIMAL(10, 2) DEFAULT NULL, -- Product price
  image VARCHAR(255) DEFAULT NULL, -- Product image URL
  status TINYINT NOT NULL DEFAULT 0, -- Product status (0 = inactive, 1 = active)
  date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Date the product was added
  UNIQUE KEY name_unique (name), -- Unique constraint for product name
  KEY idx_product_category_id (category_id), -- Index for category_id column
  CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES category (category_id) ON UPDATE CASCADE, -- Foreign key constraint for category
  CONSTRAINT fk_product_brand FOREIGN KEY (brand_id) REFERENCES brands (brand_id) -- Foreign key constraint for brand
);

CREATE TABLE inventory (
  inventory_id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL, -- Product associated with the inventory
  quantity INT NOT NULL, -- Quantity in stock
  last_updated_date DATETIME NOT NULL, -- Date the inventory was last updated
  FOREIGN KEY (product_id) REFERENCES product(product_id)
);

-- Order-related tables
CREATE TABLE orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User who placed the order
  order_date DATETIME NOT NULL, -- Date the order was placed
  total_amount DECIMAL(10, 2) NOT NULL, -- Total amount of the order
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE order_details (
  order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL, -- Order associated with the order detail
  product_id INT NOT NULL, -- Product in the order detail
  quantity INT NOT NULL, -- Quantity of the product ordered
  unit_price DECIMAL(10, 2) NOT NULL, -- Unit price of the product
  FOREIGN KEY (order_id) REFERENCES orders(order_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE order_history (
  order_history_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL, -- Order associated with the history
  user_id INT NOT NULL, -- User who placed the order
  order_date DATETIME NOT NULL, -- Date the order was placed
  total_amount DECIMAL(10, 2) NOT NULL, -- Total amount of the order
  status VARCHAR(50) NOT NULL, -- Current status of the order
  current_status VARCHAR(50) DEFAULT NULL, -- Previous status of the order
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

CREATE TABLE returns (
  return_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL, -- Order associated with the return
  return_reason TEXT NOT NULL, -- Reason for the return
  return_date DATETIME NOT NULL, -- Date the return was initiated
  FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

-- Shopping cart and wishlist tables
CREATE TABLE cart (
  cart_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User associated with the cart
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE cart_product (
  cart_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  PRIMARY KEY (cart_id, product_id),
  FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE wishlists (
  wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User associated with the wishlist
  product_id INT NOT NULL, -- Product in the wishlist
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
);

-- Other tables
CREATE TABLE reviews (
  review_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User who wrote the review
  product_id INT NOT NULL, -- Product being reviewed
  rating DECIMAL(2, 1) NOT NULL, -- Rating given by the user
  review_text TEXT, -- Text of the review
  review_date DATETIME NOT NULL, -- Date the review was written
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE ratings (
  rating_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User who gave the rating
  product_id INT NOT NULL, -- Product being rated
  rating_value DECIMAL(2, 1) NOT NULL, -- Rating value
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (product_id) REFERENCES product(product_id)
);

CREATE TABLE coupons (
  coupon_id INT AUTO_INCREMENT PRIMARY KEY,
  coupon_code VARCHAR(50) NOT NULL, -- Coupon code
  discount_amount DECIMAL(10, 2) NOT NULL, -- Discount amount
  expiration_date DATE NOT NULL -- Expiration date of the coupon
);

CREATE TABLE messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL, -- User who sent the message
  receiver_id INT NOT NULL, -- User who received the message
  message_content TEXT NOT NULL, -- Content of the message
  message_date DATETIME NOT NULL, -- Date the message was sent
  FOREIGN KEY (sender_id) REFERENCES users(user_id),
  FOREIGN KEY (receiver_id) REFERENCES users(user_id)
);

CREATE TABLE notifications (
  notification_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User associated with the notification
  notification_content TEXT NOT NULL, -- Content of the notification
  notification_date DATETIME NOT NULL, -- Date the notification was sent
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE shipping_methods (
  shipping_method_id INT AUTO_INCREMENT PRIMARY KEY,
  method_name VARCHAR(100) NOT NULL, -- Name of the shipping method
  estimated_delivery_time INT NOT NULL -- Estimated delivery time in days
);

CREATE TABLE taxes (
  tax_id INT AUTO_INCREMENT PRIMARY KEY,
  country VARCHAR(100) NOT NULL, -- Country
  state VARCHAR(100) NOT NULL, -- State or province
  tax_rate DECIMAL(5, 2) NOT NULL -- Tax rate
);

CREATE TABLE discounts (
  discount_id INT AUTO_INCREMENT PRIMARY KEY,
  discount_name VARCHAR(100) NOT NULL, -- Name of the discount
  discount_type VARCHAR(50) NOT NULL, -- Type of discount (e.g., percentage, fixed amount)
  discount_value DECIMAL(10, 2) NOT NULL -- Value of the discount
);

CREATE TABLE suppliers (
  supplier_id INT AUTO_INCREMENT PRIMARY KEY,
  supplier_name VARCHAR(255) NOT NULL, -- Name of the supplier
  contact_name VARCHAR(255) NOT NULL, -- Contact person's name
  contact_email VARCHAR(255) NOT NULL, -- Contact email address
  contact_phone VARCHAR(20) NOT NULL -- Contact phone number
);

CREATE TABLE transactions (
  transaction_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL, -- Order associated with the transaction
  transaction_date DATETIME NOT NULL, -- Date of the transaction
  amount DECIMAL(10, 2) NOT NULL, -- Amount of the transaction
  payment_method_id INT NOT NULL, -- Payment method used for the transaction
  FOREIGN KEY (order_id) REFERENCES orders(order_id),
  FOREIGN KEY (payment_method_id) REFERENCES payment_methods(payment_method_id)
);

CREATE TABLE analytics (
  analytics_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User associated with the analytics data
  page_visited VARCHAR(255) NOT NULL, -- Page visited by the user
  timestamp DATETIME NOT NULL, -- Timestamp of the page visit
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE subscriptions (
  subscription_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User associated with the subscription
  subscription_type VARCHAR(100) NOT NULL, -- Type of subscription
  start_date DATE NOT NULL, -- Start date of the subscription
  end_date DATE NOT NULL, -- End date of the subscription
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE recommendations (
  recommendation_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL, -- User for whom the recommendation is made
  recommended_product_id INT NOT NULL, -- Recommended product
  recommendation_score DECIMAL(5, 2) NOT NULL, -- Score of the recommendation
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (recommended_product_id) REFERENCES product(product_id)
);

CREATE TABLE custom_fields (
  custom_field_id INT AUTO_INCREMENT PRIMARY KEY,
  field_name VARCHAR(255) NOT NULL, -- Name of the custom field
  field_type VARCHAR(50) NOT NULL, -- Type of the custom field (e.g., text, number, date)
  field_value TEXT NOT NULL -- Value of the custom field
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
CREATE INDEX idx_wishlists_user_id ON wishlists (user_id);
CREATE INDEX idx_wishlists_product_id ON wishlists (product_id);
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

CREATE TRIGGER create_cart_after_user_registration
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM cart WHERE user_id = NEW.user_id) THEN
        INSERT INTO cart (user_id)
        VALUES (NEW.user_id);
    END IF;
END//

CREATE TRIGGER create_order_history_after_first_order
AFTER INSERT ON orders
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM order_history WHERE user_id = NEW.user_id) THEN
        INSERT INTO order_history (order_id, user_id, order_date, total_amount, status, current_status)
        VALUES (NEW.order_id, NEW.user_id, NEW.order_date, NEW.total_amount, 'new', 'new');
    END IF;
END//

CREATE TRIGGER update_order_status
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    UPDATE orders
    SET status = 'processing'
    WHERE order_id = NEW.order_id;
END//

DELIMITER ;
