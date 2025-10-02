function validateRegisterForm() {
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const number = document.getElementById('number').value;
    const gender = document.querySelector('input[name="gender"]:checked');

    if (!firstName || !lastName || !email || !password || !number || !gender) {
        alert('All fields are required!');
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address!');
        return false;
    }

    if (password.length < 6) {
        alert('Password must be at least 6 characters long!');
        return false;
    }

    if (number.length < 10) {
        alert('Please enter a valid phone number!');
        return false;
    }

    return true;
}