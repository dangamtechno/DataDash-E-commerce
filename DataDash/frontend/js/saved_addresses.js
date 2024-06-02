function showEditForm(address_id, address_type, street_address, city, state, postal_code, country) {
    document.getElementById('edit-address-id').value = address_id;
    document.getElementById('edit-address-type').value = address_type;
    document.getElementById('edit-street-address').value = street_address;
    document.getElementById('edit-city').value = city;
    document.getElementById('edit-state').value = state;
    document.getElementById('edit-postal-code').value = postal_code;
    document.getElementById('edit-country').value = country;
    document.getElementById('edit-address-form').style.display = 'block';
    window.scrollTo(0, document.body.scrollHeight); // Scroll to bottom of page
}

function hideEditForm() {
    document.getElementById('edit-address-form').style.display = 'none';
}