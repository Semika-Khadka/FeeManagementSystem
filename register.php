<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = $_POST['name'];
    $number = $_POST['number'];
    $role = $_POST['role'];

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
            // Start a transaction
            $conn->begin_transaction();
            try {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_user_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                $insert_user_stmt = $conn->prepare($insert_user_sql);
                $insert_user_stmt->bind_param("ss", $username, $hashed_password);

                if (!$insert_user_stmt->execute()) {
                    throw new Exception("Error inserting user.");
                }

                // Get the inserted user ID
                $user_id = $conn->insert_id;

                // Insert profile into profiles table
                $insert_profile_sql = "INSERT INTO profiles (user_id, name, number, role) VALUES (?, ?, ?, ?)";
                $insert_profile_stmt = $conn->prepare($insert_profile_sql);
                $insert_profile_stmt->bind_param("isss", $user_id, $name, $number, $role);

                if (!$insert_profile_stmt->execute()) {
                    throw new Exception("Error creating profile.");
                }

                // Commit the transaction
                $conn->commit();

                $_SESSION['success_message'] = "Registration successful!.";
                header("Location: welcome.php");
                exit;
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $error_message = $e->getMessage();
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
   <link rel="stylesheet" href="css/register.css"
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
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Number:</label>
            <input type="text" name="number" required>
            <label>Role:</label>
            <input type="text" name="role" required>
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
