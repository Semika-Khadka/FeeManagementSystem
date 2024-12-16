<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@300;400;500;600;700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/login.css">

   
</head>

<body>

    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required><br>
            <label>Password:</label>
            <div class="password-container">
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">Show</button>
            </div>
            <!-- <input type="password" name="password" required><br> -->
            <button type="submit" name="login">Login</button>
        </form>

        <!-- Error message (if any) -->
        <?php
        session_start();
        include 'config.php';


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'config.php';

            $username = $_POST['username'];
            $password = $_POST['password'];

            // Check if the username/email exists in the users table
            $sql = "SELECT Id, password FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if ($hashed_password && password_verify($password, $hashed_password)) {
                // Password is correct; set session variables and redirect to welcome page
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit;
            } else {
                echo '<div class="error-message">Invalid username or password.</div>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>

        <!-- Optional "Forgot Password" link -->
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>


    <link rel="stylesheet" href="./script/toggle_script.js">
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.querySelector('.toggle-password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            toggleButton.textContent = 'Show';
        }
    }
</script>


</body>

</html>