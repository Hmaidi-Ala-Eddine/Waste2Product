(function(window, document) {
  const ValidationConfig = {
    quantity: { min: 0.1, max: 999999.99, decimals: 2 },
    address: { min: 10, max: 1000, pattern: /^[a-zA-Z0-9\s\.,\-\#\/]+$/ },
    description: { max: 2000, pattern: /^[a-zA-Z0-9\s\.,\-\!\?\(\)]*$/ }
  };

  function clearFieldValidation(field) {
    field.classList.remove('is-valid', 'is-invalid');
    const feedback = field.parentNode && field.parentNode.querySelector('.invalid-feedback');
    if (feedback) feedback.style.display = 'none';
  }

  function setFieldValid(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
  }

  function setFieldInvalid(field, message) {
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    const feedback = field.parentNode && field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
      feedback.textContent = message;
      feedback.style.display = 'block';
    }
  }

  function sanitizeQuantityInput(input) {
    let value = input.value || '';
    value = value.replace(/[^0-9.]/g, '');
    const parts = value.split('.');
    if (parts.length > 2) value = parts[0] + '.' + parts.slice(1).join('');
    if (parts[1] && parts[1].length > 2) value = parts[0] + '.' + parts[1].substring(0, 2);
    input.value = value;
  }

  function sanitizeTextInput(input) {
    let value = input.value || '';
    input.value = value.replace(/[<>"'&]/g, '');
  }

  function updateCharacterCount(field, maxLength) {
    const currentLength = field.value.length;
    let countElement = field.parentNode.querySelector('.char-count');
    if (!countElement) {
      countElement = document.createElement('small');
      countElement.className = 'char-count text-muted';
      field.parentNode.appendChild(countElement);
    }
    countElement.textContent = `${currentLength}/${maxLength} characters`;
    if (currentLength > maxLength) countElement.className = 'char-count text-danger';
    else if (currentLength > maxLength * 0.9) countElement.className = 'char-count text-warning';
    else countElement.className = 'char-count text-muted';
  }

  function validateField(field, type) {
    const value = (field.value || '').trim();
    let isValid = true;
    let msg = '';
    clearFieldValidation(field);

    switch (type) {
      case 'customer':
        if (!value) { isValid = false; msg = 'Please select a customer.'; }
        break;
      case 'waste_type':
        if (!value) { isValid = false; msg = 'Please select a waste type.'; }
        break;
      case 'quantity':
        if (!value) { isValid = false; msg = 'Please enter the quantity.'; }
        else if (isNaN(value) || parseFloat(value) <= 0) { isValid = false; msg = 'Quantity must be a valid positive number.'; }
        else if (parseFloat(value) < ValidationConfig.quantity.min) { isValid = false; msg = `Quantity must be at least ${ValidationConfig.quantity.min} kg.`; }
        else if (parseFloat(value) > ValidationConfig.quantity.max) { isValid = false; msg = `Quantity cannot exceed ${ValidationConfig.quantity.max.toLocaleString()} kg.`; }
        else if (!/^\d+(\.\d{1,2})?$/.test(value)) { isValid = false; msg = 'Quantity can have maximum 2 decimal places.'; }
        break;
      case 'state':
        if (!value) { isValid = false; msg = 'Please select a governorate/state.'; }
        break;
      case 'address':
        if (!value) { isValid = false; msg = 'Please enter the pickup address.'; }
        else if (value.length < ValidationConfig.address.min) { isValid = false; msg = `Address must be at least ${ValidationConfig.address.min} characters long.`; }
        else if (value.length > ValidationConfig.address.max) { isValid = false; msg = `Address cannot exceed ${ValidationConfig.address.max} characters.`; }
        else if (!ValidationConfig.address.pattern.test(value)) { isValid = false; msg = 'Address contains invalid characters.'; }
        break;
      case 'description':
        if (value && value.length > ValidationConfig.description.max) { isValid = false; msg = `Description cannot exceed ${ValidationConfig.description.max} characters.`; }
        else if (value && !ValidationConfig.description.pattern.test(value)) { isValid = false; msg = 'Description contains invalid characters.'; }
        break;
      case 'status':
        if (!value) { isValid = false; msg = 'Please select a status.'; }
        break;
    }

    if (isValid) setFieldValid(field); else setFieldInvalid(field, msg);
    return isValid;
  }

  function addValidationListeners(form) {
    const customer = form.querySelector('select[name="user_id"]');
    const wasteType = form.querySelector('select[name="waste_type"]');
    const quantity = form.querySelector('input[name="quantity"]');
    const state = form.querySelector('select[name="state"]');
    const address = form.querySelector('textarea[name="address"]');
    const description = form.querySelector('textarea[name="description"]');
    const status = form.querySelector('select[name="status"]');
    const collector = form.querySelector('select[name="collector_id"]');

    if (customer) customer.addEventListener('change', () => validateField(customer, 'customer'));
    if (wasteType) wasteType.addEventListener('change', () => validateField(wasteType, 'waste_type'));
    if (quantity) {
      quantity.addEventListener('input', function() { sanitizeQuantityInput(this); validateField(this, 'quantity'); });
      quantity.addEventListener('blur', () => validateField(quantity, 'quantity'));
    }
    if (state) state.addEventListener('change', () => validateField(state, 'state'));
    if (address) {
      address.addEventListener('input', function() { sanitizeTextInput(this); validateField(this, 'address'); updateCharacterCount(this, ValidationConfig.address.max); });
      address.addEventListener('blur', () => validateField(address, 'address'));
    }
    if (description) description.addEventListener('input', function() { sanitizeTextInput(this); validateField(this, 'description'); updateCharacterCount(this, ValidationConfig.description.max); });
    if (status) status.addEventListener('change', () => validateField(status, 'status'));
    if (collector) collector.addEventListener('change', () => validateField(collector, 'collector'));
  }

  function validateForm(form) {
    let ok = true;
    const fields = [
      ['select[name="user_id"]', 'customer'],
      ['select[name="waste_type"]', 'waste_type'],
      ['input[name="quantity"]', 'quantity'],
      ['select[name="state"]', 'state'],
      ['textarea[name="address"]', 'address'],
      ['textarea[name="description"]', 'description'],
      ['select[name="status"]', 'status'],
    ];
    fields.forEach(([sel, type]) => {
      const el = form.querySelector(sel);
      if (el && !validateField(el, type)) ok = false;
    });
    if (!ok) {
      const firstInvalid = form.querySelector('.is-invalid');
      if (firstInvalid) {
        firstInvalid.focus();
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
    return ok;
  }

  function attachSubmitHandler(form) {
    form.addEventListener('submit', function(e) {
      if (!validateForm(form)) {
        e.preventDefault();
      }
    });
  }

  function setupForm(form) {
    if (!form) return;
    addValidationListeners(form);
    attachSubmitHandler(form);
  }

  function init() {
    setupForm(document.getElementById('addRequestForm'));
    setupForm(document.getElementById('editRequestForm'));
  }

  window.WasteRequestValidation = { init };
})(window, document);
