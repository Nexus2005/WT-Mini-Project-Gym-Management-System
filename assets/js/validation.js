// Global Validation Functions

function validateName(name) {
    // Only letters and spaces, min 2 chars
    return /^[a-zA-Z\s]{2,100}$/.test(name.trim());
}

function validateMobile(mobile) {
    // 10 digits, starts with 6-9
    return /^[6-9]\d{9}$/.test(mobile);
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validateAge(age) {
    return !isNaN(age) && age >= 10 && age <= 100;
}

function validatePrice(price) {
    return !isNaN(price) && parseFloat(price) > 0;
}
