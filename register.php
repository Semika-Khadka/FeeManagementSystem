<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Username already exists!";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ss", $username, $hashed_password);

            if ($insert_stmt->execute()) {
                $_SESSION['success_message'] = "Registration successful! Please log in.";
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Error registering user.";
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Laila:wght@300;400;500;600;700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        /* General styles */
        body {
            font-family: "Roboto Condensed", serif;
            font-style: normal;
            background: linear-gradient(to left, #A6B29D, #D8D8D8);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 2px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
        }

        h2 {
            text-align: center;
            color: #0A0908;
            margin-bottom: 20px;
            margin-top: 5px;
            font-size: 30px;
        }

        label {
            font-size: 14px;
            color: #0A0908;
            display: block;
            margin-bottom: 10px;
            margin-top: 3px;
        }

        /* the label box */
        input[type="text"],
        input[type="password"] {
            width: 93%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 10px;
            border: 1px solid #0A0908;
            font-size: 16px; 
        }

/* register button */
        button[type="submit"] {
            background-color: #0A0908;
            color: white;
            padding: 14px;
            width: 100%;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 12px;
            
        }

        button[type="submit"]:hover {
            background-color: #655B53;
            
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            <button type="submit">Register</button>
        </form>

        <?php
        if (isset($error_message)) {
            echo '<div class="error-message">' . $error_message . '</div>';
        }
        ?>
    </div>
</body>
</html>
