const form = document.getElementById('regForm');
const passwordInput = document.getElementById('password');
const passwordStrength = document.getElementById('strength');
const errorMsg = document.getElementById('error-msg');

function checkPasswordStrength(password) {
    if (password.length < 6) return 'weak';
    if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/\d/) && password.length >= 8) return 'strong';
    return 'medium';
}

passwordInput.addEventListener('input', () => {
    const strength = checkPasswordStrength(passwordInput.value);
    passwordStrength.setAttribute('data-strength', strength);
});

form.addEventListener('submit', (e) => {
    e.preventDefault();
    errorMsg.textContent = '';

    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const password = form.password.value;
    const confirm = form.confirmPassword.value;

    if (!name || !email || !password || !confirm) {
        errorMsg.textContent = 'All fields are required!';
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        errorMsg.textContent = 'Enter a valid email!';
        return;
    }

    if (password !== confirm) {
        errorMsg.textContent = 'Passwords do not match!';
        return;
    }

    if (checkPasswordStrength(password) === 'weak') {
        errorMsg.textContent = 'Password is too weak!';
        return;
    }

    alert('Registration successful!');
    form.reset();
    passwordStrength.setAttribute('data-strength', '');
});
