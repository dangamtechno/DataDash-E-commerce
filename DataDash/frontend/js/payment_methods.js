function showEditForm(payment_method_id, method_type, card_number, cvs_number, expiration_date) {
    document.getElementById('edit-payment-method-id').value = payment_method_id;
    document.getElementById('edit-method-type').value = method_type;
    document.getElementById('edit-card-number').value = card_number;
    document.getElementById('edit-cvs-number').value = cvs_number;
    document.getElementById('edit-expiration-date').value = expiration_date;
    document.getElementById('edit-payment-method-form').style.display = 'block';
    window.scrollTo(0, document.body.scrollHeight); // Scroll to bottom of page
}

function hideEditForm() {
    document.getElementById('edit-payment-method-form').style.display = 'none';
}
