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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@300;400;500;600;700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/profile.css">

</head>

<body>
    <div class="navbar">
        <h1>Profile</h1>
    </div>

    <!-- Sidebar -->
    <div class="container">
        <div class="sidebar">
            <div class="logo"><a href="./welcome.php">FeeTrack</a></div>
            <div class="nav-links">
                <a href="./welcome.php">Dashboard</a>
                <a href="./profile_screen.php">Profile</a>
                <a href="./contact__screen.php">Contact</a>
                <form action="logout.php">
                    <input type="Submit" value="Logout">
                </form>
            </div>
        </div>
        <div class="profile-container">
            <?php if (isset($profile)) { ?>
                <h2>Welcome, <?php echo htmlspecialchars($profile['name']); ?>!</h2>
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
                <h2>Profile Error</h2>
                <p><?php echo $error_message; ?></p>
            <?php } ?>
        </div>
</body>

</html>