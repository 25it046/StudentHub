```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["studentName"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";
    $year = $_POST["year"] ?? "";
    $gender = $_POST["gender"] ?? "";
    $terms = $_POST["terms"] ?? "";

    $errors = [];

    if ($name == "") {
        $errors[] = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile number must contain exactly 10 digits.";
    }

    if (!in_array($course, ["B.Tech", "M.Tech", "BCA", "MCA"], true)) {
        $errors[] = "Please select a valid course.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    if (!in_array($year, ["1", "2", "3", "4"], true)) {
        $errors[] = "Please select a valid year.";
    }

    if (!in_array($gender, ["Male", "Female", "Other"], true)) {
        $errors[] = "Please select a valid gender.";
    }

    if ($terms !== "accepted") {
        $errors[] = "You must accept the terms and conditions.";
    }

    if (count($errors) > 0) {
        echo "<h2>Registration Failed</h2>";

        foreach ($errors as $error) {
            echo "<p>" . htmlspecialchars($error, ENT_QUOTES, "UTF-8") . "</p>";
        }

        echo '<p><a href="../pages/register.html">Go Back</a></p>';
    } else {
        $file = fopen(__DIR__ . "/registrations.csv", "a");

        if ($file) {
            if (flock($file, LOCK_EX)) {
                $saved = fputcsv($file, [
                    $name,
                    $email,
                    $mobile,
                    $course,
                    $year,
                    $gender
                ]);

                flock($file, LOCK_UN);
                fclose($file);

                if ($saved !== false) {
                    echo "<h2>Registration Successful!</h2>";
                    echo "<p>Your registration has been saved.</p>";
                    echo '<p><a href="../pages/register.html">Register Another Student</a></p>';
                } else {
                    echo "<h2>Error saving registration. Please try again.</h2>";
                }
            } else {
                fclose($file);
                echo "<h2>Error saving registration. Please try again.</h2>";
            }
        } else {
            echo "<h2>Unable to open the CSV file for writing.</h2>";
        }
    }
} else {
    echo "Please submit the registration form.";
}
?>
```