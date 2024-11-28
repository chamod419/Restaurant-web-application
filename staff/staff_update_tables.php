<?php

include '../components/connect.php';

session_start();

$staff_id = $_SESSION['staff_id'];

if (!isset($staff_id)) {
    header('location:login.php');
    exit();
}

if (isset($_POST['update_table'])) {
    $table_id = $_POST['table_id'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $old_image = $_POST['old_image'];

    // Update table information
    $update_tables = $conn->prepare("UPDATE `tables` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
    $update_tables->execute([$name, $category, $details, $price, $table_id]);

    $message[] = 'Table Updated Successfully!';

    // Update image if a new one is uploaded
    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            // Update image
            $update_image = $conn->prepare("UPDATE `tables` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $table_id]);

            if ($update_image) {
                move_uploaded_file($image_tmp_name, $image_folder);
                // Remove old image
                if ($old_image && file_exists('uploaded_img/' . $old_image)) {
                    unlink('uploaded_img/' . $old_image);
                }
                $message[] = 'Image Updated Successfully!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café - Update Table</title>

    <!-- Font Awesome CDN Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="css/staff_style.css">
</head>

<body>

<?php include '../components/staff_header.php'; ?>

<section class="update-tables">
    <h1 class="title">Update Table</h1>

    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $select_tables = $conn->prepare("SELECT * FROM `tables` WHERE id = ?");
        $select_tables->execute([$update_id]);
        if ($select_tables->rowCount() > 0) {
            while ($fetch_tables = $select_tables->fetch(PDO::FETCH_ASSOC)) {
    ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="old_image" value="<?= htmlspecialchars($fetch_tables['image']); ?>">
                    <input type="hidden" name="table_id" value="<?= htmlspecialchars($fetch_tables['id']); ?>">
                    <img src="uploaded_img/<?= htmlspecialchars($fetch_tables['image']); ?>" alt="">
                    <input type="text" name="name" placeholder="Enter Table Name" required class="box" value="<?= htmlspecialchars($fetch_tables['name']); ?>">
                    <input type="number" name="price" min="0" placeholder="Enter Table Price" required class="box" value="<?= htmlspecialchars($fetch_tables['price']); ?>">
                    <select name="category" class="box" required>
                        <option value="<?= htmlspecialchars($fetch_tables['category']); ?>" selected><?= htmlspecialchars($fetch_tables['category']); ?></option>
                        <option value="Single Table">Single Table</option>
                        <!-- <option value="Double Table">Double Table</option> -->
                        <option value="Family Pack Table">Family Pack Table</option>
                        <option value="VIP Table">VIP Table</option>
                    </select>
                    <textarea name="details" required placeholder="Enter Table Details" class="box" cols="30" rows="10"><?= htmlspecialchars($fetch_tables['details']); ?></textarea>
                    <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
                    <div class="flex-btn">
                        <input type="submit" class="btn" value="Update Table" name="update_table">
                        <a href="staff_tables.php" class="option-btn">Go Back</a>
                    </div>
                </form>
    <?php
            }
        } else {
            echo '<p class="empty">No Tables Found!</p>';
        }
    } else {
        echo '<p class="empty">Invalid Request!</p>';
    }
    ?>
</section>

<?php include '../components/staff_footer.php'; ?>


<script src="js/script.js"></script>

</body>

</html>
