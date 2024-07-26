# DataDash: An E-commerce Website

DataDash is an E-commerce database system and website built with PHP, HTML, CSS, and JavaScript, designed to provide a platform for online shopping.

## Features

- **User Authentication:** Customers can register, log in, and manage their user profiles.
- **Product Catalog:** A wide range of products is available for browsing, with details including descriptions, images, and prices.
- **Shopping Cart:** Users can add items to their cart, adjust quantities, and proceed to checkout.
- **Checkout Process:** The checkout process handles order completion.
- **Order History:** Customers can view their past orders.
- **Search and Filtering:** Products can be found by name, category, or price range. Filtering options are available for easier navigation.
- **Admin Panel:** Website administrators have a dedicated panel for management.

## Development Environment Setup

### Required Software:
- PHP 8.1 or later
- MySQL 8.0 or later
- Apache Web Server (or another compatible web server)
- Composer (dependency manager)
- Text Editor or IDE (Integrated Development Environment)

### Steps:
1. Install PHP, MySQL, and Apache.
2. Configure Apache to serve the project directory.
3. Create a MySQL database for DataDash.
4. Import the `schema.sql` file into the database. (Include the actual file path)
5. Clone the repository: `git clone https://github.com/dangamtechno/DataDash-E-commerce`
6. Install project dependencies using Composer: `composer install`
7. Update the database connection settings in `backend/config/config.php`:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'datadash_db');
?>
```

8. Use the `populate_db.sql` file to add sample data to the database.
9. Start your local web server and access the website by navigating to the `public` folder.

## Server Deployment

1. Upload project files to the server.
2. Configure the web server (Apache or Nginx) to point to the public directory.
3. Create a MySQL database on the server.
4. Import the `schema.sql` file.
5. Update database connection settings in `backend/config/config.php` with server credentials.

## Database Backups and Restores

### Backups:
Use mysqldump command (or a database backup tool) to regularly create backups of the database:
```
mysqldump -u [username] -p[password] [database_name] > backup.sql
```

### Restores:
Use mysql command to restore a database from a backup file:
```
mysql -u [username] -p[password] [database_name] < backup.sql
```

## Usage

1. Access the website in your web browser.
2. Sign up for a new account or log in if you already have one.
3. Explore the available products in the catalog.
4. Add items you want to purchase to your shopping cart.
5. Go to the checkout to complete your order.
6. Check your order history to review past purchases.

## Known Issues and Limitations

- URL Rewriting (User Story 38): Not fully implemented due to time constraints.
- Admin Discount Management (User Story 40): The UI for managing discounts in the admin panel is not yet complete.

## Future Enhancements (Wishlist)

1. Improved User Interface:
   - Responsive design for mobile devices.
   - Implement more modern CSS frameworks for a visually appealing and consistent user experience.

2. Enhanced Search Functionality:
   - Integrate auto-complete suggestions in the search bar.
   - Implement advanced filtering options (e.g., by product attributes, price ranges).

3. Product Recommendations:
   - Develop a recommendation system that suggests products based on user browsing history or past purchases.

4. Social Media Integration:
   - Allow users to share products or their wishlist on social media platforms.
  
5. Mailer:
   - Get PHPMailer running.

6. Email Marketing:
   - Integrate with an email marketing service to send promotional emails and newsletters.

## Contributing

We welcome contributions! If you encounter any problems or have ideas for enhancements, please create an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).
