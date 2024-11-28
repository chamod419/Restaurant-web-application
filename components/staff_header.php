<?php
// staff_header.php
include '../components/connect.php';

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure staff_id is set
if (!isset($_SESSION['staff_id'])) {
    header('Location: staff_login.php');
    exit();
}

$staff_id = $_SESSION['staff_id'];

try {
    // Fetch staff profile
    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$staff_id]);
    
    // Check if any result was returned
    if ($fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC)) {
        $profile_image = $fetch_profile['image'] ?? 'default.png'; // Fallback to a default image if none is set
        $profile_name = htmlspecialchars($fetch_profile['name']); // Sanitize the output
    } else {
        $profile_image = 'default.png'; // Fallback to a default image if no profile is found
        $profile_name = 'Unknown User';
    }
} catch (PDOException $e) {
    // Handle database errors
    $profile_image = 'default.png';
    $profile_name = 'Error Fetching Profile';
    error_log("Error fetching staff profile: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Header</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">Staff<span>Panel</span></a>

        <nav class="navbar">
            <a href="../staff/dashboard.php">Dashboard</a>
            <a href="staff_reservations.php">Reservations Placed</a>
            <a href="staff_preorders.php">Pre-Orders Placed</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <a href="../staff/staff_account.php" class="btn">Update Profile</a>
            <a href="../components/staff_logout.php" class="delete-btn">Logout</a>
            <div class="flex-btn">
                <a href="../staff/staff_login.php" class="option-btn">Login</a>
                <a href="../staff/register_staff.php" class="option-btn">Register</a>
            </div>
        </div>
    </section>
</header>

</body>
</html>
