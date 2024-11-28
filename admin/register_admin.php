<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

if (isset($_POST['submit'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Check if passwords match
    if ($pass !== $cpass) {
        $message[] = 'Confirm password does not match!';
    } else {
        // Check if username already exists
        $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
        $select_admin->execute([$name]);

        if ($select_admin->rowCount() > 0) {
            $message[] = 'Username already exists!';
        } else {
            // Hash the password
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new admin
            $insert_admin = $conn->prepare("INSERT INTO `admin` (name, password) VALUES (?, ?)");
            $insert_admin->execute([$name, $hashed_pass]);
            $message[] = 'New admin registered!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Register Admin Section Starts -->
<section class="form-container">
    <form action="" method="POST">
        <h3>Register New Admin</h3>
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
            }
        }
        ?>
        <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="cpass" maxlength="20" required placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Register Now" name="submit" class="btn">
    </form>
</section>
<!-- Register Admin Section Ends -->

<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
