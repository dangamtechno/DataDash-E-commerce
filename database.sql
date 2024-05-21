CREATE DATABASE datadash;
USE datadash;

CREATE TABLE users (
user_id INT PRIMARY KEY,
first_name VARCHAR(255),
last_name VARCHAR(255),
username VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
password_hash VARCHAR(255),
phone VARCHAR(20),
registration_date DATETIME
);

CREATE TABLE category (
  id INT NOT NULL AUTO_INCREMENT,
  status TINYINT(1) NOT NULL DEFAULT 0,
  name VARCHAR(45) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY name_unique (name)
);

CREATE TABLE banner (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  description VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL,
  status TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE product (
  id INT NOT NULL AUTO_INCREMENT,
  category_id INT NOT NULL,
  name VARCHAR(45) NOT NULL,
  description VARCHAR(255) DEFAULT NULL,
  price FLOAT DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  status TINYINT NOT NULL DEFAULT 0,
  date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY name_unique (name),
  KEY fk_1_idx (category_id),
  CONSTRAINT fk_1 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE CASCADE
);

CREATE TABLE orders (
order_id INT PRIMARY KEY,
user_id INT,
order_date DATETIME,
total_amount DECIMAL(10, 2),
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE order_details (
order_detail_id INT PRIMARY KEY,
order_id INT,
product_id INT,
quantity INT NOT NULL,
unit_price DECIMAL(10, 2) NOT NULL,
FOREIGN KEY (order_id) REFERENCES orders(order_id),
FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE cart (
cart_id INT PRIMARY KEY,
user_id INT NOT NULL,
product_id INT,
quantity INT,
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE addresses (
address_id INT PRIMARY KEY,
user_id INT,
address_type VARCHAR(50),
street_address VARCHAR(255),
city VARCHAR(100),
state VARCHAR(100),
postal_code VARCHAR(20),
country VARCHAR(100),
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE payment_methods (
payment_method_id INT PRIMARY KEY,
user_id INT,
method_type VARCHAR(50),
card_number VARCHAR(20),
expiration_date DATE,
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE reviews (
review_id INT PRIMARY KEY,
user_id INT,
product_id INT,
rating DECIMAL(2, 1),
review_text TEXT,
review_date DATETIME,
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE ratings (
rating_id INT PRIMARY KEY,
user_id INT,
product_id INT,
rating_value DECIMAL(2, 1),
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE coupons (
coupon_id INT PRIMARY KEY,
coupon_code VARCHAR(50),
discount_amount DECIMAL(10, 2),
expiration_date DATE
);

CREATE TABLE wishlists (
wishlist_id INT PRIMARY KEY,
user_id INT,
product_id INT,
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE messages (
message_id INT PRIMARY KEY,
sender_id INT,
receiver_id INT,
message_content TEXT NOT NULL,
message_date DATETIME,
FOREIGN KEY (sender_id) REFERENCES users(user_id),
FOREIGN KEY (receiver_id) REFERENCES users(user_id)
);

CREATE TABLE notifications (
notification_id INT PRIMARY KEY,
user_id INT,
notification_content TEXT NOT NULL,
notification_date DATETIME,
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE shipping_methods (
shipping_method_id INT PRIMARY KEY,
method_name VARCHAR(100),
estimated_delivery_time INT
);

CREATE TABLE taxes (
tax_id INT PRIMARY KEY,
country VARCHAR(100),
state VARCHAR(100),
tax_rate DECIMAL(5, 2)
);

CREATE TABLE discounts (
discount_id INT PRIMARY KEY,
discount_name VARCHAR(100),
discount_type VARCHAR(50),
discount_value DECIMAL(10, 2)
);

CREATE TABLE returns (
return_id INT PRIMARY KEY,
order_id INT,
return_reason TEXT,
return_date DATETIME,
FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

CREATE TABLE suppliers (
supplier_id INT PRIMARY KEY,
supplier_name VARCHAR(255),
contact_name VARCHAR(255),
contact_email VARCHAR(255),
contact_phone VARCHAR(20)
);

CREATE TABLE brands (
brand_id INT PRIMARY KEY,
brand_name VARCHAR(255)
);

CREATE TABLE inventory (
inventory_id INT PRIMARY KEY,
product_id INT,
quantity INT,
last_updated_date DATETIME,
FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE transactions (
transaction_id INT PRIMARY KEY,
order_id INT,
transaction_date DATETIME,
amount DECIMAL(10, 2),
payment_method_id INT,
FOREIGN KEY (order_id) REFERENCES orders(order_id),
FOREIGN KEY (payment_method_id) REFERENCES payment_methods(payment_method_id)
);

CREATE TABLE analytics (
analytics_id INT PRIMARY KEY,
user_id INT,
page_visited VARCHAR(255),
timestamp DATETIME,
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE subscriptions (
subscription_id INT PRIMARY KEY,
user_id INT,
subscription_type VARCHAR(100),
start_date DATE,
end_date DATE,
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE recommendations (
recommendation_id INT PRIMARY KEY,
user_id INT,
recommended_product_id INT,
recommendation_score DECIMAL(5, 2),
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (recommended_product_id) REFERENCES product(id)
);

CREATE TABLE custom_fields (
custom_field_id INT PRIMARY KEY,
field_name VARCHAR(255),
field_type VARCHAR(50),
field_value TEXT
);

CREATE TABLE cart_product (
  cart_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  PRIMARY KEY (cart_id, product_id),
  FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
  FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE order_history (
  order_id INT PRIMARY KEY,
  user_id INT NOT NULL,
  order_date DATETIME NOT NULL,
  total_amount DECIMAL(10, 2) NOT NULL,
  status VARCHAR(50) NOT NULL,
  current_status VARCHAR(50) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);
