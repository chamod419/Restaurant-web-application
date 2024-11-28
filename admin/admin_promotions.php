<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

if (isset($_POST['update_promotion'])) {
    $product_id = $_POST['product_id'];
    $promotion = isset($_POST['promotion']) ? 1 : 0;

    $update_promotion = $conn->prepare("UPDATE products SET promotion = :promotion WHERE id = :id");
    $update_promotion->bindParam(':promotion', $promotion, PDO::PARAM_INT);
    $update_promotion->bindParam(':id', $product_id, PDO::PARAM_INT);
    $update_promotion->execute();
}

$select_products = $conn->prepare("SELECT * FROM products");
$select_products->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Promotions</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/admin_header.php'; ?>

<section class="admin-promotions">
    <h1 class="title">Manage Promotions</h1>
    <div class="box-container">
        <?php while ($product = $select_products->fetch(PDO::FETCH_ASSOC)) { ?>
            <form action="admin_promotions.php" method="post" class="box">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']); ?>">
                <div class="name"><?= htmlspecialchars($product['name']); ?></div>
                <div class="price">Rs.<?= htmlspecialchars($product['price']); ?>/-</div>
                <label>
                    Promotion:
                    <input type="checkbox" name="promotion" <?= $product['promotion'] ? 'checked' : ''; ?>>
                </label>
                <button type="submit" name="update_promotion" class="btn">Update</button>
            </form>
        <?php } ?>
    </div>
</section>

<?php include '../components/admin_footer.php'; ?>

</body>
</html>
