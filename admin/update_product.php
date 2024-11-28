<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

if (isset($_POST['update'])) {

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);
    $promotion = $_POST['promotion'];
    $promotion = filter_var($promotion, FILTER_SANITIZE_STRING);

    $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ?, details = ?, promotion = ? WHERE id = ?");
    $update_product->execute([$name, $category, $price, $details, $promotion, $pid]);

    $message[] = 'Product updated!';

    $old_image = $_POST['old_image'];
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/'.$image;

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $pid]);
            move_uploaded_file($image_tmp_name, $image_folder);
            if (file_exists('../uploaded_img/'.$old_image)) {
                unlink('../uploaded_img/'.$old_image);
            }
            $message[] = 'Image updated!';
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
    <title>Update Meals</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

<section class="update-product">

    <h1 class="title">Update Meals</h1>

    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $show_products->execute([$update_id]);
        if ($show_products->rowCount() > 0) {
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
        <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
        <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
        <span>Update Name</span>
        <input type="text" required placeholder="Enter meal name" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
        <span>Update Price</span>
        <input type="number" min="0" max="9999999999" required placeholder="Enter meal price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
        <span>Update Category</span>
        <select name="category" class="box" required>
            <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
            <option value="Sri Lankan food">Sri Lankan food</option>
            <option value="Chinese food">Chinese food</option>
            <option value="Italian food">Italian food</option>
            <option value="Indian food">Indian food</option>
        </select>
        <span>Update Details</span>
        <textarea name="details" class="box" required placeholder="Enter meal details"><?= $fetch_products['details']; ?></textarea>
        <span>Update Promotion</span>
        <input type="number" min="0" max="100" required placeholder="Enter promotion percentage" name="promotion" onkeypress="if(this.value.length == 3) return false;" class="box" value="<?= $fetch_products['promotion']; ?>">
        <span>Update Image</span>
        <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
        <div class="flex-btn">
            <input type="submit" value="Update" class="btn" name="update">
            <a href="products.php" class="option-btn">Go Back</a>
        </div>
    </form>
    <?php 
            }
        } else {
            echo '<p class="empty">No meal added yet!</p>';
        }
    } else {
        echo '<p class="empty">No meal selected for update!</p>';
    }
    ?>

</section>

<!-- update product section ends -->



<?php include '../components/admin_footer.php'; ?>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
