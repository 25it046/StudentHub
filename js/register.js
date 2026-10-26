document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let mobile = document.getElementById("mobile").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let course = document.getElementById("course").value;
    let year = document.getElementById("year").value;
    let gender = document.querySelector('input[name="gender"]:checked');
    let terms = document.getElementById("terms").checked;

    let valid = true;

    document.querySelectorAll("#registerForm span").forEach(function(s) {
        s.textContent = "";
        s.style.color = "red";
    });

    document.getElementById("result").textContent = "";

    if (!/^[A-Za-z ]{3,}$/.test(name)) {
        document.getElementById("nameError").textContent = "Enter a valid name (at least 3 letters).";
        valid = false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById("emailError").textContent = "Enter a valid email.";
        valid = false;
    }

    if (!/^[6-9][0-9]{9}$/.test(mobile)) {
        document.getElementById("mobileError").textContent = "Enter a valid 10-digit Indian mobile number.";
        valid = false;
    }

    if (!/^(?=.*[A-Za-z])(?=.*[0-9]).{8,}$/.test(password)) {
        document.getElementById("passwordError").textContent = "Password must have 8 characters, a letter and a number.";
        valid = false;
    }

    if (password !== confirmPassword || confirmPassword === "") {
        document.getElementById("confirmPasswordError").textContent = "Passwords do not match.";
        valid = false;
    }

    if (course === "") {
        document.getElementById("courseError").textContent = "Please select a course.";
        valid = false;
    }

    if (year === "") {
        document.getElementById("yearError").textContent = "Please select a year.";
        valid = false;
    }

    if (!gender) {
        document.getElementById("genderError").textContent = "Please select your gender.";
        valid = false;
    }

    if (!terms) {
        document.getElementById("termsError").textContent = "Please accept the terms and conditions.";
        valid = false;
    }

    if (valid) {
        document.getElementById("result").textContent = "Registration successful!";
        document.getElementById("result").style.color = "green";
    }
});