<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

// Add new event
if (isset($_POST['add_events'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    $select_events = $conn->prepare("SELECT * FROM events WHERE name = ?");
    $select_events->execute([$name]);

    if ($select_events->rowCount() > 0) {
        $message[] = 'Event already exists!';
    } else {
        $insert_events = $conn->prepare("INSERT INTO events (name, price, details) VALUES (?, ?, ?)");
        $insert_events->execute([$name, $price, $details]);
        $message[] = 'Event Added Successfully!';
    }
}

// Delete event
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_events = $conn->prepare("DELETE FROM events WHERE id = ?");
    $delete_events->execute([$delete_id]);
    header('location:admin_events.php');
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

    <!-- Add Events Section -->
    <section class="add-events">
        <h1 class="title">Add New Event</h1>
        <form action="" method="POST">
            <div class="inputBox">
                <input type="text" name="name" class="box" required placeholder="Enter Event Name">
            </div>
            <div class="inputBox">
                <input type="number" min="0" name="price" class="box" required placeholder="Enter Event Price">
            </div>
            <textarea name="details" class="box" required placeholder="Enter Event Details" cols="30" rows="10"></textarea>
            <input type="submit" class="btn" value="Add Event" name="add_events">
        </form>
    </section>

    <!-- Show Events Section -->
    <section class="show-events">
        <h1 class="title">Events Added</h1>
        <div class="box-container"> 
            <?php
            $show_events = $conn->prepare("SELECT * FROM events");
            $show_events->execute();
            if ($show_events->rowCount() > 0) {
                while ($fetch_events = $show_events->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="box">
                    <div class="price">Rs. <?= $fetch_events['price']; ?></div>
                    <div class="name"><?= $fetch_events['name']; ?></div>
                    <div class="details"><?= $fetch_events['details']; ?></div>
                    <div class="flex-btn">
                        <a href="admin_update_events.php?id=<?= $fetch_events['id']; ?>" class="option-btn">Update</a>
                        <a href="admin_events.php?delete=<?= $fetch_events['id']; ?>" class="delete-btn" onclick="return confirm('Delete this Event?');">Delete</a>
                    </div>
                </div>
            <?php
                }
            } else {
                echo '<p class="empty">No Events Added Yet!</p>';
            }
            ?>
        </div>
    </section>


    <?php include '../components/admin_footer.php'; ?>

    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>
</body>
</html>
