<?php
include '../components/connect.php'; 

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

// Update event
if (isset($_POST['update_events'])) {
    $event_id = $_POST['event_id'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    $update_events = $conn->prepare("UPDATE events SET name = ?, price = ?, details = ? WHERE id = ?");
    $update_events->execute([$name, $price, $details, $event_id]);
    $message[] = 'Event Updated Successfully!';
}

if (isset($_GET['id'])) {
    $update_id = $_GET['id'];
    $select_events = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $select_events->execute([$update_id]);
    if ($select_events->rowCount() == 0) {
        echo '<p class="empty">Event Not Found!</p>';
        exit();
    }
    $fetch_events = $select_events->fetch(PDO::FETCH_ASSOC);
} else {
    echo '<p class="empty">Invalid Request!</p>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

    <?php include '../components/admin_header.php'; ?>

    <!-- Update Events Section -->
    <section class="update-events">
        <h1 class="title">Update Event</h1>
        <form action="" method="POST">
            <input type="hidden" name="event_id" value="<?= $fetch_events['id']; ?>">
            <input type="text" name="name" class="box" required placeholder="Enter Event Name" value="<?= $fetch_events['name']; ?>">
            <input type="number" name="price" class="box" required placeholder="Enter Event Price" value="<?= $fetch_events['price']; ?>">
            <textarea name="details" class="box" required placeholder="Enter Event Details" cols="30" rows="10"><?= $fetch_events['details']; ?></textarea>
            <input type="submit" class="btn" value="Update Event" name="update_events">
            <a href="admin_events.php" class="option-btn">Go Back</a>
        </form>
    </section>

    <?php include '../components/admin_footer.php'; ?>

    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>
</body>
</html>
