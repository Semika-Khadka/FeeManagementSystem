<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch user and profile details
$sql = "
    SELECT 
        u.username, 
        p.name, 
        p.number, 
        p.role 
    FROM 
        users u 
    INNER JOIN 
        profiles p 
    ON 
        u.id = p.user_id 
    WHERE 
        u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
} else {
    $error_message = "Profile not found.";
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #A6B29D, #D8D8D8);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .profile-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .profile-item {
            font-size: 18px;
            margin: 10px 0;
            color: #555;
        }

        .profile-item span {
            font-weight: bold;
            color: #000;
        }

        .logout-button {
            background-color: #0A0908;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #655B53;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if (isset($profile)) { ?>
            <h1>Welcome, <?php echo htmlspecialchars($profile['name']); ?>!</h1>
            <div class="profile-item">
                <span>Username:</span> <?php echo htmlspecialchars($profile['username']); ?>
            </div>
            <div class="profile-item">
                <span>Name:</span> <?php echo htmlspecialchars($profile['name']); ?>
            </div>
            <div class="profile-item">
                <span>Number:</span> <?php echo htmlspecialchars($profile['number']); ?>
            </div>
            <div class="profile-item">
                <span>Role:</span> <?php echo htmlspecialchars($profile['role']); ?>
            </div>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        <?php } else { ?>
            <h1>Profile Error</h1>
            <p><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
