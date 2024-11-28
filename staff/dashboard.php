<?php
include '../components/connect.php';

session_start();

// Check if the staff is logged in
if (!isset($_SESSION['staff_id'])) {
    header('Location: staff_login.php');
    exit();
}

// Fetching statistics from the database
$staff_id = $_SESSION['staff_id'];

// Initialize variables
$total_pending_reservations = 0;
$total_completed_reservations = 0;
$number_of_reservations = 0;
$number_of_tables = 0;
$total_pendings_orders = 0;
$total_completed_orders = 0;
$number_of_orders = 0;

// Helper function to execute queries and fetch results
function fetchData($query, $params = []) {
    global $conn;
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

// Total pending reservations
try {
    $select_pending_reservations = fetchData("SELECT COUNT(*) as count FROM `reservations` WHERE payment_status = ?", ['pending']);
    $total_pending_reservations = $select_pending_reservations->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    echo "Error fetching pending reservations: " . $e->getMessage();
}

// Total completed reservations
try {
    $select_completed_reservations = fetchData("SELECT COUNT(*) as count FROM `reservations` WHERE payment_status = ?", ['completed']);
    $total_completed_reservations = $select_completed_reservations->fetch(PDO::FETCH_ASSOC)['count'];
} catch (PDOException $e) {
    echo "Error fetching completed reservations: " . $e->getMessage();
}

// Number of reservations
try {
    $select_reservations = fetchData("SELECT * FROM `reservations`");
    $number_of_reservations = $select_reservations->rowCount();
} catch (PDOException $e) {
    echo "Error fetching number of reservations: " . $e->getMessage();
}

// Number of tables
try {
    $select_tables = fetchData("SELECT * FROM `tables`");
    $number_of_tables = $select_tables->rowCount();
} catch (PDOException $e) {
    echo "Error fetching number of tables: " . $e->getMessage();
}

// Total pending orders
try {
    $select_pendings_orders = fetchData("SELECT * FROM `orders` WHERE payment_status = ?", ['pending']);
    while ($fetch_pendings_orders = $select_pendings_orders->fetch(PDO::FETCH_ASSOC)) {
        if (isset($fetch_pendings_orders['total_price'])) {
            $total_pendings_orders += $fetch_pendings_orders['total_price'];
        }
    }
} catch (PDOException $e) {
    echo "Error fetching pending orders: " . $e->getMessage();
}

// Total completed orders
try {
    $select_completed_orders = fetchData("SELECT * FROM `orders` WHERE payment_status = ?", ['completed']);
    while ($fetch_completed_orders = $select_completed_orders->fetch(PDO::FETCH_ASSOC)) {
        if (isset($fetch_completed_orders['total_price'])) {
            $total_completed_orders += $fetch_completed_orders['total_price'];
        }
    }
} catch (PDOException $e) {
    echo "Error fetching completed orders: " . $e->getMessage();
}

// Number of orders
try {
    $select_orders = fetchData("SELECT * FROM `orders`");
    $number_of_orders = $select_orders->rowCount();
} catch (PDOException $e) {
    echo "Error fetching number of orders: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
    
</head>
<body>

<?php include '../components/staff_header.php'; ?>

<section class="dashboard">
    <h1 class="heading">Welcome to the Staff Dashboard</h1>

    <div class="box-container">
        <div class="box">
            <h3><?= $total_pending_reservations; ?></h3>
            <p>Pending Reservations</p>
            <a href="staff_reservations.php" class="btn">See Reservations</a>
        </div>

        <div class="box">
            <h3><?= $total_completed_reservations; ?></h3>
            <p>Completed Reservations</p>
            <a href="staff_reservations.php" class="btn">See Reservations</a>
        </div>

        <div class="box">
            <h3><?= $number_of_reservations; ?></h3>
            <p>Reservations Placed</p>
            <a href="staff_reservations.php" class="btn">See Reservations</a>
        </div>

        <div class="box">
            <h3><?= $number_of_tables; ?></h3>
            <p>Tables Added</p>
            <a href="staff_tables.php" class="btn">See Tables</a>
        </div>
        
        <div class="box">
            <h3>Rs.<?= number_format($total_pendings_orders, 2); ?>/-</h3>
            <p>Total Pendings (Pre-Orders)</p>
            <a href="staff_preorders.php" class="btn">See Pre-Orders</a>
        </div>

        <div class="box">
            <h3>Rs.<?= number_format($total_completed_orders, 2); ?>/-</h3>
            <p>Completed Pre-Orders</p>
            <a href="staff_preorders.php" class="btn">See Pre-Orders</a>
        </div>

        <div class="box">
            <h3><?= $number_of_orders; ?></h3>
            <p>Pre-Orders Placed</p>
            <a href="staff_preorders.php" class="btn">See Pre-Orders</a>
        </div>
    </div>
</section>

<?php include '../components/staff_footer.php'; ?>

<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
