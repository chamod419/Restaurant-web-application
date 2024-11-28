<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit; // Ensure no further code execution after redirection
}

if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];
} else {
    header('location:staff_accounts.php');
    exit; // Ensure no further code execution after redirection
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    if ($pass != $cpass) {
        $message[] = 'Confirm password not matched!';
    } else {
        $update_staff = $conn->prepare("UPDATE `staff` SET name = ?, password = ? WHERE id = ?");
        $update_staff->execute([$name, $cpass, $staff_id]);
        $message[] = 'Staff details updated!';
    }
}

// Fetch staff record
$select_staff = $conn->prepare("SELECT * FROM `staff` WHERE id = ?");
$select_staff->execute([$staff_id]);
$fetch_staff = $select_staff->fetch(PDO::FETCH_ASSOC);

// Check if the record exists
if (!$fetch_staff) {
    $message[] = 'Staff not found!';
    // Redirect or handle the error accordingly
    header('location:staff_accounts.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Staff</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/staff_header.php'; ?>

<!-- update staff section starts -->

<section class="form-container">

   <form action="" method="POST">
      <h3>Update Staff Profile</h3>
      <input type="text" name="name" value="<?= htmlspecialchars($fetch_staff['name']); ?>" maxlength="20" required placeholder="Enter username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" placeholder="Enter new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" maxlength="20" placeholder="Confirm new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update Now" name="update" class="btn">
   </form>

</section>

<!-- update staff section ends -->


<?php include '../components/staff_footer.php'; ?>

<script src="../js/admin_script.js"></script>


</body>
</html>
