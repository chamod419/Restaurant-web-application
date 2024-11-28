<?php
@include '../components/connect.php';

session_start();

$staff_id = $_SESSION['staff_id'];

if (!isset($staff_id)) {
    header('location:staff_login.php');
    exit();
}

if (isset($_POST['add_tables'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);

    $image = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_tables = $conn->prepare("SELECT * FROM `tables` WHERE name = ?");
    $select_tables->execute([$name]);

    if ($select_tables->rowCount() > 0) {
        $message[] = 'Table Category Already Exists!';
    } else {
        $insert_tables = $conn->prepare("INSERT INTO `tables`(name, category, details, price, image) VALUES(?,?,?,?,?)");
        $insert_tables->execute([$name, $category, $details, $price, $image]);

        if ($insert_tables) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'New Table Category Added!';
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Retrieve the image name from the 'tables' table
    $select_delete_image = $conn->prepare("SELECT image FROM `tables` WHERE id = ?");
    $select_delete_image->execute([$delete_id]);
    $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);

    // Delete the image from the server
    if ($fetch_delete_image) {
        unlink('../uploaded_img/' . $fetch_delete_image['image']);
    }

    // Delete the table entry from the 'tables' table
    $delete_tables = $conn->prepare("DELETE FROM `tables` WHERE id = ?");
    $delete_tables->execute([$delete_id]);

    // Delete related reservations from the 'reservations' table
    // Replace 'table_id' with the actual column name in the reservations table
    $delete_reservations = $conn->prepare("DELETE FROM `reservations` WHERE table_id = ?");
    $delete_reservations->execute([$delete_id]);

    header('location:staff_tables.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café - Staff Tables</title>

    <!-- Font Awesome CDN Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>



<?php include '../components/staff_header.php'; ?>

<section class="add-tables">
    <h1 class="title">Add New Table</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="flex">
            <input type="text" name="name" class="box" required placeholder="Enter Table Name">
            <select name="category" class="box" required>
                <option value="" selected disabled>Select Category</option>
                <option value="Single Table">Single Table</option>
                <option value="Family Pack Table">Family Pack Table</option>
                <option value="VIP Table">VIP Table</option>
            </select>
        </div>
        <div class="inputBox">
            <input type="number" min="0" name="price" class="box" required placeholder="Enter Table Price">
        </div>
        <div class="inputBox">
            <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
        </div>
        <textarea name="details" class="box" required placeholder="Enter Table Details" cols="30" rows="10"></textarea>
        <input type="submit" class="btn" value="Add Table" name="add_tables">
    </form>
</section>

<section class="show-tables">
    <h1 class="title">Tables Added</h1>
    <div class="box-container">
        <?php
        $show_tables = $conn->prepare("SELECT * FROM `tables`");
        $show_tables->execute();
        if ($show_tables->rowCount() > 0) {
            while ($fetch_tables = $show_tables->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <div class="price">Rs.<?= htmlspecialchars($fetch_tables['price']); ?>/-</div>
            <img src="../uploaded_img/<?= htmlspecialchars($fetch_tables['image']); ?>" alt="">
            <div class="name"><?= htmlspecialchars($fetch_tables['name']); ?></div>
            <div class="category"><?= htmlspecialchars($fetch_tables['category']); ?></div>
            <div class="details"><?= htmlspecialchars($fetch_tables['details']); ?></div>
            <div class="flex-btn">
                <a href="staff_update_tables.php?update=<?= htmlspecialchars($fetch_tables['id']); ?>" class="option-btn">Update</a>
                <a href="staff_tables.php?delete=<?= htmlspecialchars($fetch_tables['id']); ?>" class="delete-btn" onclick="return confirm('Delete this Table?');">Delete</a>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No Tables Added Yet!</p>';
        }
        ?>
    </div>
</section>

<?php include '../components/staff_footer.php'; ?>

<!-- Custom JS File Link -->
<script src="../js/script.js"></script>

</body>
</html>
