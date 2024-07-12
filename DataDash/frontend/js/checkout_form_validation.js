// Disable Review Order button initially
document.getElementById('review-order-button').disabled = true;

// Add event listeners to radio buttons
const shippingAddressRadios = document.querySelectorAll('input[name="shipping-address"]');
const paymentMethodRadios = document.querySelectorAll('input[name="payment-method"]');

// Check if all required fields are filled out
function checkRequiredFields() {
  let allFieldsFilled = true;

  // Check shipping address fields
  if (shippingAddressRadios.length > 0) {
    if (!document.querySelector('input[name="shipping-address"]:checked')) {
      allFieldsFilled = false;
    }
  }

  // Check payment method fields
  if (paymentMethodRadios.length > 0) {
    if (!document.querySelector('input[name="payment-method"]:checked')) {
      allFieldsFilled = false;
    }
  }

  // Enable Review Order button if all fields are filled
  if (allFieldsFilled) {
    document.getElementById('review-order-button').disabled = false;
  } else {
    document.getElementById('review-order-button').disabled = true;
  }
}

// Add event listeners to radio buttons to enable Review Order button
shippingAddressRadios.forEach(radio => {
  radio.addEventListener('change', () => {
    document.getElementById('shipping_address_id').value = radio.value;
    checkRequiredFields();
  });
});

paymentMethodRadios.forEach(radio => {
  radio.addEventListener('change', () => {
    document.getElementById('payment_method_id').value = radio.value;
    checkRequiredFields();
  });
});