-- Populate the category table
INSERT INTO category (category_name) VALUES
  ('Smartphones'),
  ('Tablets'),
  ('Headphones'),
  ('Laptops'),
  ('Smartwatches'),
  ('Cameras'),
  ('Earbuds'),
  ('Televisions'),
  ('Gaming Consoles'),
  ('Smart Speakers'),
  ('Chargers'),
  ('Keyboards'),
  ('Computer Mice'),
  ('Storage Devices'),
  ('Virtual Reality'),
  ('Wi-Fi Routers');

-- Populate the brands table
INSERT INTO brands (brand_name) VALUES
  ('Apple'),
  ('Samsung'),
  ('Sony'),
  ('Google'),
  ('Bose'),
  ('Canon'),
  ('JBL'),
  ('LG'),
  ('Microsoft'),
  ('Anker'),
  ('Logitech'),
  ('Razer Inc.'),
  ('Meta'),
  ('Dell Technologies');

-- Insert 20 products into the product table
INSERT INTO product (category_id, brand_id, name, description, price, image, status)
VALUES
  (1, 1, 'iPhone 13', 'The latest iPhone with advanced features.', 999.99, 'iphone_13.jpg', 1),
  (2, 2, 'Galaxy Tab S8', 'High-performance tablet for work and play.', 799.99, 'galaxy_tab_s8.jpg', 1),
  (3, 3, 'Sony WH-1000XM4', 'Immersive sound experience with noise cancellation.', 349.99, 'sony_wh_1000xm4.jpg', 1),
  (4, 1, 'MacBook Pro', 'Powerful laptop for professional use.', 1999.99, 'macbook_pro.jpg', 1),
  (5, 2, 'Galaxy Watch 4', 'Stay connected and track your fitness goals.', 349.99, 'galaxy_watch_4.jpg', 1),
  (6, 6, 'Canon EOS R5', 'Capture stunning photos and videos with professional quality.', 3499.99, 'canon_eos_r5.jpg', 1),
  (7, 1, 'AirPods Pro', 'True wireless earbuds for seamless audio experience.', 249.99, 'airpods_pro.jpg', 1),
  (8, 8, 'LG OLED C1', 'Immerse yourself in a world of entertainment with lifelike visuals.', 1999.99, 'lg_oled_c1.jpg', 1),
  (9, 3, 'PlayStation 5', 'Experience the next generation of gaming with powerful performance.', 499.99, 'playstation_5.jpg', 1),
  (10, 1, 'HomePod Mini', 'Voice-controlled speaker for hands-free convenience.', 99.99, 'homepod_mini.jpg', 1),
  (11, 10, 'Anker Wireless Charger', 'Charge your devices without the hassle of cables.', 29.99, 'anker_wireless_charger.jpg', 1),
  (12, 11, 'Logitech G Pro X', 'Wireless keyboard for comfortable typing experience.', 129.99, 'logitech_g_pro_x.jpg', 1),
  (13, 12, 'Razer DeathAdder Elite', 'Precision gaming mouse for competitive edge.', 69.99, 'razer_deathadder_elite.jpg', 1),
  (14, 2, 'Samsung T7 SSD', 'High-speed storage solution for your digital content.', 199.99, 'samsung_t7_ssd.jpg', 1),
  (15, 13, 'Oculus Quest 2', 'Immerse yourself in virtual reality experiences.', 299.99, 'oculus_quest_2.jpg', 1),
  (16, 4, 'Google Nest WiFi', 'Reliable and fast WiFi for your home.', 199.99, 'google_nest_wifi.jpg', 1),
  (2, 1, 'iPad Air', 'Powerful tablet for work and creativity.', 599.99, 'ipad_air.jpg', 1),
  (7, 5, 'Bose QuietComfort Earbuds', 'Wireless earbuds with noise cancellation.', 279.99, 'bose_quietcomfort_earbuds.jpg', 1),
  (4, 14, 'Dell XPS 15', 'Thin and powerful laptop for professionals.', 1599.99, 'dell_xps_15.jpg', 1),
  (5, 4, 'Fitbit Versa 3', 'Fitness smartwatch with heart rate monitoring.', 229.99, 'fitbit_versa_3.jpg', 1),
  (1, 4, 'Google Pixel 8 Pro', 'A premium Android smartphone with advanced camera features.', 1099.99, 'google_pixel_8_pro.jpg', 1);

-- Insert inventory records for the products
INSERT INTO inventory (product_id, quantity, last_updated_date)
VALUES
  (1, 5, NOW()),
  (2, 10, NOW()),
  (3, 2, NOW()),
  (4, 15, NOW()),
  (5, 3, NOW()),
  (6, 8, NOW()),
  (7, 1, NOW()),
  (8, 4, NOW()),
  (9, 12, NOW()),
  (10, 1, NOW()),
  (11, 7, NOW()),
  (12, 1, NOW()),
  (13, 3, NOW()),
  (14, 1, NOW()),
  (15, 9, NOW()),
  (16, 1, NOW()),
  (17, 6, NOW()),
  (18, 1, NOW()),
  (19, 2, NOW()),
  (20, 1, NOW()),
  (21, 5, NOW());

-- Populate the coupons table
INSERT INTO coupons (coupon_code, discount_amount, expiration_date)
VALUES
  ('SUMMER10', 10.00, '2024-08-31'),
  ('NEWYEAR20', 20.00, '2025-01-31'),
  ('WELCOME5', 5.00, '2024-12-31'),
  ('HOLIDAY15', 15.00, '2024-12-25'),
  ('CYBER30', 30.00, '2024-11-30');

-- Populate the shipping_methods table
INSERT INTO shipping_methods (method_name, estimated_delivery_time)
VALUES
  ('Standard Shipping', 5),
  ('Express Shipping', 2),
  ('Overnight Shipping', 1),
  ('Free Shipping', 7);

-- Populate the taxes table
INSERT INTO taxes (country, state, tax_rate)
VALUES
  ('United States', 'California', 8.25),
  ('United States', 'Texas', 6.25),
  ('United States', 'New York', 8.875),
  ('Canada', 'Ontario', 13.00),
  ('Canada', 'Quebec', 14.975);

-- Populate the discounts table
INSERT INTO discounts (discount_name, discount_type, discount_value)
VALUES
  ('Holiday Sale', 'percentage', 20.00),
  ('Clearance', 'percentage', 50.00),
  ('Buy One Get One', 'fixed', 10.00),
  ('Free Shipping', 'fixed', 0.00),
  ('Student Discount', 'percentage', 15.00);

-- Populate the suppliers table
INSERT INTO suppliers (supplier_name, contact_name, contact_email, contact_phone)
VALUES
  ('Techtronics Ltd.', 'James Smith', 'james.smith@techtronics.com', '555-1234'),
  ('Gadget Galaxy', 'Emily Johnson', 'emily.johnson@gadgetgalaxy.com', '555-5678'),
  ('Electro Enterprises', 'David Wilson', 'david.wilson@electroenterprises.com', '555-9012'),
  ('Innovative Electronics', 'Sophia Lee', 'sophia.lee@innovativeelectronics.com', '555-3456'),
  ('Digital Dynamics Inc.', 'William Brown', 'william.brown@digitaldynamics.com', '555-7890');

-- Populate the custom_fields table
INSERT INTO custom_fields (field_name, field_type, field_value, product_id)
VALUES
  ('Warranty Period', 'number', '12', 1),
  ('Color Options', 'text', 'Red, Blue, Green', 2),
  ('Delivery Instructions', 'text', 'Leave at front door', NULL),
  ('Gift Wrap', 'boolean', '1', NULL),
  ('Size', 'text', 'Small, Medium, Large', 3);


-- Populate user table
INSERT INTO users (first_name, last_name, username, email, password_hash, favorite_movie, phone)
VALUES
  ('John', 'Doe', 'johndoe', 'johndoe@example.com', 'pass1234', 'The Matrix', '123-456-7890');


-- Populate wishlist table
INSERT INTO wishlist (user_id, wishlist_name)
VALUES
  (1, 'Home'),  
  (1, 'Holiday'),  
  (1, 'Work');

-- Populate wishlist_products table
INSERT INTO wishlist_products (wishlist_id, product_id, user_id)
VALUES
  (1, 1, 1),
  (2, 2, 1),
  (3, 3, 1);

-- Populate the reviews table with ratings and reviews for each product
INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES
    (1, 1, 5, 'Excellent phone with great camera and performance!'),
    (1, 2, 4, 'Love the tablet for reading and browsing the web.'),
    (1, 3, 3, 'Decent headphones, but the noise cancellation could be better.'),
    (1, 4, 5, 'A powerful laptop that handles everything I throw at it.'),
    (1, 5, 4, 'Stylish watch with good battery life and fitness tracking features.'),
    (1, 6, 5, 'Amazing camera for capturing stunning photos and videos.'),
    (1, 7, 4, 'Good earbuds with comfortable fit and decent sound quality.'),
    (1, 8, 5, 'Exceptional TV with incredible picture quality and sound.'),
    (1, 9, 4, 'Great gaming console with impressive graphics and performance.'),
    (1, 10, 3, 'Compact speaker with decent sound but lacks bass.'),
    (1, 11, 4, 'Convenient wireless charger that works well with most phones.'),
    (1, 12, 4, 'Comfortable keyboard with good typing experience.'),
    (1, 13, 5, 'Amazing mouse for gaming with precise tracking and responsive buttons.'),
    (1, 14, 4, 'Fast SSD with ample storage space.'),
    (1, 15, 5, 'Immersive VR experience with good graphics and controls.'),
    (1, 16, 4, 'Reliable WiFi router with good coverage and speed.'),
    (1, 17, 4, 'Good tablet for everyday use, but not as powerful as the Galaxy Tab S8.'),
    (1, 18, 5, 'Excellent earbuds with fantastic noise cancellation and sound quality.'),
    (1, 19, 4, 'Solid laptop for everyday tasks and light gaming.'),
    (1, 20, 3, 'Decent fitness watch but could use some improvements on the GPS accuracy.');



