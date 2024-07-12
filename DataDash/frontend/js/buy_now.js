function submitForms(formId) {
    const form = document.getElementById(formId);
    const formData = new FormData(form);

    fetch(form.action, {
      method: form.method,
      body: formData
    })
    .then(response => {
      // Handle response from server
    })
    .catch(error => {
      // Handle error
    });
  }
    const productForm = document.getElementById('product-form');

    productForm.addEventListener('submit', (event) => {
      event.preventDefault();

      const action = event.target.querySelector('button[type="submit"]').dataset.action;

      if (action === 'add-to-cart') {
        productForm.action = '../../backend/utils/add_to_cart.php';
        productForm.submit();
      } else if (action === 'buy-now') {
        productForm.action = 'checkout.php';

        // Update the form data for checkout:
        productForm.querySelector('input[name="action"]').value = 'checkout';
        productForm.querySelector('input[name="selected_products"]').value = `[${product_data['product_id']}]`;
        productForm.querySelector('input[name="selected_quantities"]').value = `{ "${product_data['product_id']}" : ${productForm.querySelector('input[name="quantity"]').value} }`;

        productForm.submit();
      }
    });