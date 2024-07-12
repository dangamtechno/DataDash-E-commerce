# DataDash: An E-commerce Website

DataDash is a modern and responsive e-commerce website built using PHP, HTML, CSS, and JavaScript. It provides a user-friendly platform for customers to browse and purchase products online.

## Features

- **User Authentication:** Customers can create accounts, log in, and manage their profiles. Passwords are securely stored using industry-standard hashing techniques.
- **Product Catalog:** Browse through a wide range of products with detailed descriptions, images, and pricing information.
- **Shopping Cart:** Add products to the shopping cart, update quantities, and proceed to checkout.
- **Secure Checkout Process:** Integration with trusted payment gateways (e.g., Stripe, PayPal) ensures secure payment processing.
- **Order History:** Customers can view their order history and track the status of their orders.
- **Search and Filtering:** Search for products by name, category, or price range, and apply filters for easy navigation.
- **Product Reviews and Ratings:** Customers can leave reviews and rate products, enhancing the shopping experience.
- **Admin Panel:** Administrators have access to a comprehensive admin panel for managing products, orders, customers, banners, website settings, and more.

## Installation

1. Clone the repository: `https://github.com/dangamtechno/DataDash-E-commerce.git`
2. Import the `schema.sql` file into your MySQL database.
3. Import the `populate_db.sql` file into your MySQL database (remember to adapt image paths and use HTTPS for image sources).
4. Configure the database connection details in `backend/config/config.php`.
5. Start a local web server (e.g., Apache, Nginx) and navigate to the `public` folder to access the website.

## Usage

1. Open the website in your web browser.
2. Create a new account or log in with an existing one.
3. Browse the product catalog and add items to your shopping cart.
4. Proceed to checkout and complete the order process.
5. View your order history and track the status of your orders.

## Future Development

- **Enhanced UI/UX:** Continuously improve the website's interface and user experience.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).
