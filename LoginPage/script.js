document.addEventListener('DOMContentLoaded', function() {
    // Attach the main validation logic to the form's submit event
    const form = document.getElementById('registrationForm');
    form.addEventListener('submit', handleFormSubmit);
    preventDefault();
    localStorage.setItem('fullname',this.documentElementById('fullName').value);
    localStorage.setItem('studentID',this.documentElementById('studentID').value);
    
    // Add blur event listeners for real-time validation
    document.getElementById('fullName').addEventListener('blur', validateFullName);
    document.getElementById('studentId').addEventListener('blur', validateStudentId);
    document.getElementById('email').addEventListener('blur', validateEmail);
    document.getElementById('username').addEventListener('blur', validateUsername);
    document.getElementById('password').addEventListener('blur', validatePassword);
    document.getElementById('confirmPassword').addEventListener('blur', validateConfirmPassword);
    document.getElementById('phone').addEventListener('blur', validatePhone);
    
});


// This new function handles the form submission
function handleFormSubmit(event) {
    // This is the most important line: it stops the page from reloading
    event.preventDefault();

    // Run all individual validation checks
    const isFullNameValid = validateFullName();
    const isStudentIdValid = validateStudentId();
    const isEmailValid = validateEmail();
    const isUsernameValid = validateUsername();
    const isPasswordValid = validatePassword();
    const isConfirmPasswordValid = validateConfirmPassword();
    const isPhoneValid = validatePhone();
    
    
    if (isFullNameValid && isStudentIdValid && isEmailValid && isUsernameValid && isPasswordValid && isConfirmPasswordValid && isPhoneValid) {
        alert("Registration Successful!");

        
        document.getElementById('displayFullName').textContent = document.getElementById('fullName').value.trim();
        document.getElementById('displayStudentId').textContent = document.getElementById('studentId').value.trim();
        document.getElementById('displayEmail').textContent = document.getElementById('email').value.trim();
        document.getElementById('displayUsername').textContent = document.getElementById('username').value.trim();
        document.getElementById('displayPhone').textContent = document.getElementById('phone').value.trim();
        // document.getElementById('displaypassword').textContent = document.getElementById('password').value.trim();

        
        document.getElementById('displayData').classList.remove('hidden');
        document.getElementById('registrationForm').reset(); 
    }
    Window.location.href = 'index.html';
}


// --- All your individual validation functions (validateFullName, etc.) remain exactly the same below this line ---

function validateFullName() {
    const fullName = document.getElementById('fullName');
    const fullNameError = document.getElementById('fullNameError');
    const nameRegex = /^[a-zA-Z\s]+$/;

    if (!nameRegex.test(fullName.value.trim())) {
        fullNameError.textContent = 'Only letters and spaces are allowed.';
        fullName.classList.add('error-input');
        return false;
    } else {
        fullNameError.textContent = '';
        fullName.classList.remove('error-input');
        return true;
    }
}

function validateStudentId() {
    const studentId = document.getElementById('studentId');
    const studentIdError = document.getElementById('studentIdError');
    const studentIdRegex = /^[a-zA-Z0-9]+$/;

    if (!studentIdRegex.test(studentId.value.trim())) {
        studentIdError.textContent = 'Alphanumeric characters only.';
        studentId.classList.add('error-input');
        return false;
    } else {
        studentIdError.textContent = '';
        studentId.classList.remove('error-input');
        return true;
    }
}

function validateEmail() {
    
    const email = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    // Only allow emails ending with @charusat.edu.in
    const emailRegex = /^[a-zA-Z0-9._%+-]+@charusat\.edu\.in$/;
    if (!emailRegex.test(email.value.trim())) {
        emailError.textContent = 'Email must be a valid CHARUSAT email (e.g., user@charusat.edu.in).';
        email.classList.add('error-input');
        return false;
    } else {
        emailError.textContent = '';
        email.classList.remove('error-input');
        return true;
    }
}

function validateUsername() {
    const username = document.getElementById('username');
    const usernameError = document.getElementById('usernameError');
    const usernameRegex = /^[a-zA-Z0-9]{5,15}$/;

    if (!usernameRegex.test(username.value.trim())) {
        usernameError.textContent = 'Alphanumeric, 5-15 characters.';
        username.classList.add('error-input');
        return false;
    } else {
        usernameError.textContent = '';
        username.classList.remove('error-input');
        return true;
    }
}

function validatePassword() {
    const password = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (!passwordRegex.test(password.value)) {
        passwordError.textContent = 'At least 8 chars, 1 letter, 1 digit, 1 special char.';
        password.classList.add('error-input');
        return false;
    } else {
        passwordError.textContent = '';
        password.classList.remove('error-input');
        return true;
    }
}

function validateConfirmPassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    if (password !== confirmPassword.value) {
        confirmPasswordError.textContent = 'Passwords do not match.';
        confirmPassword.classList.add('error-input');
        return false;
    } else {
        confirmPasswordError.textContent = '';
        confirmPassword.classList.remove('error-input');
        return true;
    }
}

function validatePhone() {
    const phone = document.getElementById('phone');
    const phoneError = document.getElementById('phoneError');
    const phoneRegex = /^[0-9]{10}$/;

    if (!phoneRegex.test(phone.value.trim())) {
        phoneError.textContent = 'Must be a 10-digit mobile number.';
        phone.classList.add('error-input');
        return false;
    } else {
        phoneError.textContent = '';
        phone.classList.remove('error-input');
        return true;
    }
}