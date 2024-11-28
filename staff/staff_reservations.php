<?php

include '../components/connect.php';

session_start();

$staff_id = $_SESSION['staff_id'];

if(!isset($staff_id)){
   header('location:staff_login.php');
   exit();
}

if(isset($_POST['update_reservations'])){

    $reservation_id = $_POST['reservation_id'];
    if(isset($_POST['update_payment'])) {
        $update_payment = $_POST['update_payment'];
        $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
        $update_reservations = $conn->prepare("UPDATE `reservations` SET payment_status = ? WHERE id = ?");
        $update_reservations->execute([$update_payment, $reservation_id]);
        $message[] = 'Reservation has been updated!';
    } else {
        $message[] = 'Please select a payment status.';
    }
 
};
 
 if(isset($_GET['delete'])){
 
    $delete_id = $_GET['delete'];
    $delete_reservations = $conn->prepare("DELETE FROM `reservations` WHERE id = ?");
    $delete_reservations->execute([$delete_id]);
    header('location:staff_reservations.php');
 
 }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations - The Gallery Café</title>

    <!-- font awesome cdn links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">

    <style>
        .count {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<?php include '../components/staff_header.php'; ?>

    <section class="reservations">

        <h1 class="title">Reservations</h1>

        <div class="box-container">

            <?php
            $select_reservations = $conn->prepare("SELECT * FROM `reservations`");
            $select_reservations->execute();
            if ($select_reservations->rowCount() > 0) {
                while ($fetch_reservations = $select_reservations->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <p> Reservation ID : <span><?= $fetch_reservations['id']; ?></span> </p>
                        <p> Customer ID : <span><?= $fetch_reservations['user_id']; ?> </span></p>
                        <p> Customer Name : <span><?= $fetch_reservations['name']; ?></span> </p>
                        <p> Customer Email : <span><?= $fetch_reservations['email']; ?></span> </p>
                        <p> No. of Persons : <span><?= $fetch_reservations['number_of_persons']; ?></span> </p>
                        <p> Reservation Date : <span><?= $fetch_reservations['date']; ?></span> </p>
                        <p> Reservation Time : <span><?= $fetch_reservations['time']; ?></span> </p>
                        <p> Payment Status : <span><?= $fetch_reservations['payment_status']; ?></span> </p>
                        <form action="" method="POST">
                            <input type="hidden" name="reservation_id" value="<?= $fetch_reservations['id']; ?>">
                            <select name="update_payment" class="drop-down">
                                <option value="" selected disabled><?= $fetch_reservations['payment_status']; ?></option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" name="update_reservations" class="option-btn" value="update">
                                <a href="staff_reservations.php?delete=<?= $fetch_reservations['id']; ?>" class="delete-btn" onclick="return confirm('Delete this reservation?');">Delete</a>
                            </div>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no reservations booked yet!</p>';
            }
            ?>

        </div>

    </section>

    <?php include '../components/staff_footer.php'; ?>

    <script src="../js/admin_script.js"></script>

</body>

</html>
