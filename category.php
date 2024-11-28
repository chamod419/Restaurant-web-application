<?php
include 'components/connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

include 'components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- Header section starts -->
    <?php include 'components/user_header.php'; ?>
    <!-- Header section ends -->

    <!-- Category section starts -->
    <section class="products">
        <h1 class="tittle">menu</h1>

        <div class="box-container">
            <?php
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $category = $_GET['category'];

                $select_products = $conn->prepare("SELECT * FROM products WHERE category = :category");
                $select_products->bindParam(':category', $category, PDO::PARAM_STR);
                $select_products->execute();

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
                            <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
                            <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
                            <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
                            <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
                            <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
                            <a href="category.php?category=<?= htmlspecialchars($fetch_products['category']); ?>" class="cat"><?= htmlspecialchars($fetch_products['category']); ?></a>
                            <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                            <div class="flex">
                                <div class="price"><span>Rs.</span><?= htmlspecialchars($fetch_products['price']); ?>/-</div>
                                <input type="number" name="qty" class="qty" value="1" min="1" max="99">
                            </div>
                        </form>
                        <?php
                    }
                } else {
                    echo '<p class="empty">No products available in this category.</p>';
                }
            } else {
                echo '<p class="empty">No category selected.</p>';
            }
            ?>
        </div>
    </section>
    <!-- Category section ends -->

    <!-- Footer section starts -->
    <?php include 'components/footer.php'; ?>
    <!-- Footer section ends -->

    <!-- Custom JS file link -->
    <script src="js/script.js"></script>

</body>

</html>