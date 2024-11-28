<?php

@include 'config.php';

session_start();

$staff_id = $_SESSION['staff_id'];

if (!isset($staff_id)) {
    header('location:login.php');
    exit();
}

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];

    if (isset($_POST['update'])) {
        $reservation_status = $_POST['reservation_status'];
        $update_reservation = $conn->prepare("UPDATE `reservations` SET reservation_status = ? WHERE id = ?");
        $update_reservation->execute([$reservation_status, $reservation_id]);
        header('location:staff_reservations.php');
    }

    $select_reservation = $conn->prepare("SELECT * FROM `reservations` WHERE id = ?");
    $select_reservation->execute([$reservation_id]);
    $fetch_reservation = $select_reservation->fetch(PDO::FETCH_ASSOC);
} else {
    header('location:staff_reservations.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Reservation - The Gallery Café</title>

    <!-- font awesome cdn links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

    <?php include 'staff_header.php'; ?>

    <section class="update-reservation">
        <h1 class="title">Update Reservation</h1>

        <form action="" method="post">
            <p>Reservation ID: <?= $fetch_reservation['id']; ?></p>
            <p>Customer ID: <?= $fetch_reservation['user_id']; ?></p>
            <p>Customer Name: <?= $fetch_reservation['customer_name']; ?></p>
            <p>Customer Email: <?= $fetch_reservation['customer_email']; ?></p>
            <p>Customer Phone: <?= $fetch_reservation['customer_phone']; ?></p>
            <p>Reservation Date: <?= $fetch_reservation['reservation_date']; ?></p>
            <p>Reservation Time: <?= $fetch_reservation['reservation_time']; ?></p>
            <p>Payment Status: <?= $fetch_reservation['payment_status']; ?></p>
            <p>Reservation Status: <?= $fetch_reservation['reservation_status']; ?></p>
            <select name="reservation_status" required>
                <option value="pending" <?= $fetch_reservation['reservation_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?= $fetch_reservation['reservation_status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="completed" <?= $fetch_reservation['reservation_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?= $fetch_reservation['reservation_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <input type="submit" name="update" value="Update Reservation" class="btn">
        </form>
    </section>

    <?php include 'staff_footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>
