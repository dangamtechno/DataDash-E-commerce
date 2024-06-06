-- Populate the category table
INSERT INTO category (category_name) VALUES
  ('Electronics'),
  ('Clothing'),
  ('Books'),
  ('Home & Kitchen'),
  ('Beauty & Personal Care'),
  ('Sports & Outdoors'),
  ('Toys & Games'),
  ('Automotive'),
  ('Office Products'),
  ('Pet Supplies');

-- Populate the brands table
INSERT INTO brands (brand_name) VALUES
  ('Apple'),
  ('Samsung'),
  ('Sony'),
  ('Nike'),
  ('Adidas'),
  ('Penguin'),
  ('HarperCollins'),
  ('KitchenAid'),
  ('Dyson'),
  ('Callaway');

-- Insert 20 products into the product table
INSERT INTO product (category_id, brand_id, name, description, price, image, status)
VALUES
  (1, 1, 'Product 1', 'Description for Product 1', 19.99, 'product1.jpg', 1),
  (1, 2, 'Product 2', 'Description for Product 2', 24.99, 'product2.jpg', 1),
  (2, 3, 'Product 3', 'Description for Product 3', 14.99, 'product3.jpg', 1),
  (2, 1, 'Product 4', 'Description for Product 4', 29.99, 'product4.jpg', 1),
  (3, 2, 'Product 5', 'Description for Product 5', 39.99, 'product5.jpg', 1),
  (3, 3, 'Product 6', 'Description for Product 6', 49.99, 'product6.jpg', 1),
  (1, 1, 'Product 7', 'Description for Product 7', 9.99, 'product7.jpg', 1),
  (1, 2, 'Product 8', 'Description for Product 8', 12.99, 'product8.jpg', 1),
  (2, 3, 'Product 9', 'Description for Product 9', 17.99, 'product9.jpg', 1),
  (2, 1, 'Product 10', 'Description for Product 10', 22.99, 'product10.jpg', 1),
  (3, 2, 'Product 11', 'Description for Product 11', 27.99, 'product11.jpg', 1),
  (3, 3, 'Product 12', 'Description for Product 12', 32.99, 'product12.jpg', 1),
  (1, 1, 'Product 13', 'Description for Product 13', 37.99, 'product13.jpg', 1),
  (1, 2, 'Product 14', 'Description for Product 14', 42.99, 'product14.jpg', 1),
  (2, 3, 'Product 15', 'Description for Product 15', 47.99, 'product15.jpg', 1),
  (2, 1, 'Product 16', 'Description for Product 16', 52.99, 'product16.jpg', 1),
  (3, 2, 'Product 17', 'Description for Product 17', 57.99, 'product17.jpg', 1),
  (3, 3, 'Product 18', 'Description for Product 18', 62.99, 'product18.jpg', 1),
  (1, 1, 'Product 19', 'Description for Product 19', 67.99, 'product19.jpg', 1),
  (1, 2, 'Product 20', 'Description for Product 20', 72.99, 'product20.jpg', 1);


-- Populate the product table
-- INSERT INTO product (category_id, brand_id, name, description, price, image, status)
-- VALUES
--  (1, 1, 'iPhone 13', 'Apple iPhone 13 256GB', 999.99, 'iphone13.jpg', 1),
--  (1, 2, 'Galaxy S22', 'Samsung Galaxy S22 5G', 799.99, 'galaxys22.jpg', 1),
-- (2, 4, 'Air Force 1', 'Nike Air Force 1 Sneakers', 89.99, 'airforce1.jpg', 1),
--  (3, 6, 'The Kite Runner', 'The Kite Runner by Khaled Hosseini', 12.99, 'kiterunner.jpg', 1),
--  (4, 8, 'Stand Mixer', 'KitchenAid Artisan Stand Mixer', 349.99, 'standmixer.jpg', 1),
--  (5, NULL, 'Vitamin C Serum', 'Vitamin C Serum for Face', 19.99, 'vitaminc.jpg', 1),
--  (6, 10, 'Golf Clubs', 'Callaway Strata Golf Club Set', 249.99, 'golfclubs.jpg', 1),
--  (7, NULL, 'Lego City', 'Lego City Police Station', 59.99, 'legocity.jpg', 1),
-- (8, NULL, 'Car Wax', 'Meguiar\'s Ultimate Liquid Wax', 24.99, 'carwax.jpg', 1),
--  (9, NULL, 'Office Chair', 'Ergonomic Office Chair', 99.99, 'officechair.jpg', 1);


-- Insert inventory records for the products
-- Products 1 and 5 will have a quantity greater than 1
INSERT INTO inventory (product_id, quantity, last_updated_date)
VALUES
  (1, 5, NOW()),
  (2, 1, NOW()),
  (3, 1, NOW()),
  (4, 1, NOW()),
  (5, 3, NOW()),
  (6, 1, NOW()),
  (7, 1, NOW()),
  (8, 1, NOW()),
  (9, 1, NOW()),
  (10, 1, NOW()),
  (11, 1, NOW()),
  (12, 1, NOW()),
  (13, 1, NOW()),
  (14, 1, NOW()),
  (15, 1, NOW()),
  (16, 1, NOW()),
  (17, 1, NOW()),
  (18, 1, NOW()),
  (19, 1, NOW()),
  (20, 1, NOW());

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
  ('Acme Electronics', 'John Doe', 'john.doe@acme.com', '555-1234'),
  ('Fashion Suppliers Inc.', 'Jane Smith', 'jane.smith@fashionsuppliers.com', '555-5678'),
  ('Book Distributors LLC', 'Bob Johnson', 'bob.johnson@bookdistributors.com', '555-9012'),
  ('Kitchen Essentials Co.', 'Sarah Lee', 'sarah.lee@kitchenessentials.com', '555-3456'),
  ('Beauty World Inc.', 'Michael Brown', 'michael.brown@beautyworld.com', '555-7890');

-- Populate the custom_fields table
INSERT INTO custom_fields (field_name, field_type, field_value)
VALUES
  ('Warranty Period', 'number', '12'),
  ('Color Options', 'text', 'Red, Blue, Green'),
  ('Delivery Instructions', 'text', 'Leave at front door'),
  ('Gift Wrap', 'boolean', '1'),
  ('Size', 'text', 'Small, Medium, Large');