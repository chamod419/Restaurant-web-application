<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'components/connect.php'; 

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $number_of_persons = $_POST['number'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $table_category = $_POST['table_category']; // New field for table category

    // Database connection using PDO
    $db_name = "mysql:host=localhost;dbname=cafe_db";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO($db_name, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO reservations 
        (user_id, name, number_of_persons, email, date, time, table_category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $number_of_persons, $email, $date, $time, $table_category]);

        echo "Reservation successful!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>

    <!-- font awesome cdn links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">


</head>

<body>

<?php include 'components/user_header.php'; ?>

<div class="heading">
    <h3>reservation</h3>
    <p> <span>Reservation /<a href="home.php"> Home</a></span></p>
</div>

<section class="checkout-reservations">
    <form action="" method="POST">
        <h3 class="title">Booking Your Favorite Table</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Name :</span>
                <input type="text" name="name" placeholder="Enter your name" class="box" required>
            </div>
            <div class="inputBox">
                <span>Number Of Persons :</span>
                <input type="number" name="number" placeholder="Number Of Persons" class="box" required>
            </div>
            <div class="inputBox">
                <span>Email :</span>
                <input type="email" name="email" placeholder="Enter your Email" class="box" required>
            </div>
            <div class="inputBox">
                <span>Date :</span>
                <input type="date" name="date" class="box" required>
            </div>
            <div class="inputBox">
                <span>Time :</span>
                <input type="time" name="time" class="box" required>
            </div>
            <div class="inputBox">
                <span>Table Category :</span>
                <select name="table_category" class="box" required>
                    <option value="" selected disabled>Select Category</option>
                    <option value="Single Table">Single Table</option>
                    <option value="Family Pack Table">Family Pack Table</option>
                    <option value="Business Pack Table">Business Pack Table</option>
                </select>
            </div>
        </div>
        <input type="submit" value="Reserve Now" class="btn">
    </form>
</section>



<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>

</html>
