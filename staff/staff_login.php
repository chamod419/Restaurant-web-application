<?php
include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    $select_staff = $conn->prepare("SELECT * FROM `staff` WHERE username = ?");
    $select_staff->execute([$username]);

    if ($select_staff->rowCount() > 0) {
        $fetch_staff = $select_staff->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $fetch_staff['password'])) {
            $_SESSION['staff_id'] = $fetch_staff['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            $message[] = 'Incorrect password!';
        }
    } else {
        $message[] = 'Username not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<section class="form-container">
    <form action="" method="POST">
        <h3>Staff Login</h3>
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
            }
        }
        ?>
        <input type="text" name="username" maxlength="50" required placeholder="Enter username" class="box">
        <input type="password" name="password" maxlength="20" required placeholder="Enter password" class="box">
        <input type="submit" value="Login" name="submit" class="btn">
    </form>
</section>

</body>
</html>
