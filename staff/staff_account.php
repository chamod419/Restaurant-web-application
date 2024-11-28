<?php
include '../components/connect.php';

session_start();

$staff_id = $_SESSION['staff_id'];

if (!isset($staff_id)) {
    header('location:login.php');
    exit();
}

$message = []; 

if (isset($_POST['update_profile'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

    // Handle password update
    $old_pass = isset($_POST['old_pass']) ? $_POST['old_pass'] : null;
    $update_pass = isset($_POST['update_pass']) ? $_POST['update_pass'] : null;
    $new_pass = isset($_POST['new_pass']) ? $_POST['new_pass'] : null;
    $confirm_pass = isset($_POST['confirm_pass']) ? $_POST['confirm_pass'] : null;

    if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        $fetch_profile_stmt = $conn->prepare("SELECT password FROM `staff` WHERE id = ?");
        $fetch_profile_stmt->execute([$staff_id]);
        $fetch_profile = $fetch_profile_stmt->fetch(PDO::FETCH_ASSOC);

        if ($fetch_profile === false) {
            $message[] = 'Error fetching profile data.';
        } else {
            if (!password_verify($update_pass, $fetch_profile['password'])) {
                $message[] = 'Old password does not match!';
            } elseif ($new_pass !== $confirm_pass) {
                $message[] = 'Confirm password does not match!';
            } else {
                $new_pass_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $update_pass_query = $conn->prepare("UPDATE `staff` SET password = ? WHERE id = ?");
                $update_pass_query->execute([$new_pass_hashed, $staff_id]);
                $message[] = 'Password updated successfully!';
            }
        }
    }

    // Update profile information
    $update_profile = $conn->prepare("UPDATE `staff` SET name = ? WHERE id = ?");
    $update_profile->execute([$name, $staff_id]);
    $message[] = 'Profile updated successfully!';
}

// Fetch user profile data
$fetch_profile_stmt = $conn->prepare("SELECT * FROM `staff` WHERE id = ?");
$fetch_profile_stmt->execute([$staff_id]);
$fetch_profile = $fetch_profile_stmt->fetch(PDO::FETCH_ASSOC);

if ($fetch_profile === false) {
    $message[] = 'Error fetching profile data.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>

    <!-- font awesome cdn links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

<?php include '../components/staff_header.php'; ?>

<section class="update-profile">
    <h1 class="title">Update Profile</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="box-container">
            <div class="box">
                <span>Name:</span>
                <input type="text" name="name" value="<?= htmlspecialchars($fetch_profile['name'] ?? ''); ?>" placeholder="Update name" required class="box">
            </div>
            <div class="box">
                <span>Old Password:</span>
                <input type="password" name="update_pass" placeholder="Enter old password" class="box">
                <span>New Password:</span>
                <input type="password" name="new_pass" placeholder="Enter new password" class="box">
                <span>Confirm New Password:</span>
                <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
            </div>
        </div>
        <div class="flex-btn">
            <input type="submit" class="btn" value="Update Profile" name="update_profile">
            <a href="staff_page.php" class="option-btn">Go Back</a>
        </div>
    </form>
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
        }
    }
    ?>
</section>

<?php include '../components/staff_footer.php'; ?>

<!-- custom js file link -->
<script src="../js/admin_script.js"></script>

</body>

</html>
