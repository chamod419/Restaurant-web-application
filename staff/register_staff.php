<?php
include '../components/connect.php';


session_start();

if (isset($_SESSION['admin_id'])) {
    header('location:dashboard.php');
    exit();
}

$message = []; 

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $select_staff = $conn->prepare("SELECT * FROM `staff` WHERE username = ?");
    $select_staff->execute([$username]);

    if ($select_staff->rowCount() > 0) {
        array_push($message, 'Username already exists!');
    } else {
        // Insert new staff
        $insert_staff = $conn->prepare("INSERT INTO `staff` (name, username, password, role) VALUES (?, ?, ?, ?)");
        $insert_staff->execute([$name, $username, $hashed_password, $role]);
        array_push($message, 'New staff registered!');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Staff</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/staff_header.php'; ?>

<!-- Staff Registration Section Starts -->
<section class="form-container">
    <form action="" method="POST">
        <h3>Register New Staff</h3>
        <?php
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
            }
        }
        ?>
        <input type="text" name="name" maxlength="50" required placeholder="Enter staff name" class="box">
        <input type="text" name="username" maxlength="50" required placeholder="Enter username" class="box">
        <input type="password" name="password" maxlength="20" required placeholder="Enter password" class="box">
        <select name="role" class="box" required>
            <option value="Admin">Admin</option>
            <option value="Operational">Operational</option>
        </select>
        <input type="submit" value="Register Staff" name="submit" class="btn">
    </form>
</section>
<!-- Staff Registration Section Ends -->



<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
