<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
   exit; // Ensure script stops executing after redirect
}

if(isset($_POST['submit'])){
   $address = $_POST['no'] .', '.$_POST['street'].', '.$_POST['village'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   try {
       $update_address = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?");
       $update_address->execute([$address, $user_id]);
       $message[] = 'Address saved!';
   } catch (PDOException $e) {
       if ($e->getCode() == 'HY000') {
           include 'components/connect.php'; 
           $update_address = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?");
           $update_address->execute([$address, $user_id]);
           $message[] = 'Address saved after reconnecting!';
       } else {
           $message[] = 'Error: ' . $e->getMessage();
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
   <title>Update Address</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- header section starts  -->
<?php include 'components/user_header.php' ?>
<!-- header section ends  -->

<!-- update_address section starts  -->

<section class="from-container">
   <form action="" method="post">
      <h3>Your Address</h3>

      <input type="text" class="type">

      <input type="text" name="no" maxlength="50" required placeholder="Number of house," class="box">
      <input type="text" name="street" maxlength="50" required placeholder="Name of road," class="box">
      <input type="text" name="village" maxlength="50" required placeholder="Name of the locality or village," class="box">
      <input type="text" name="city" maxlength="50" required placeholder="City." class="box">
      <input type="number" name="pin_code" required class="box" placeholder="Enter your pin code" maxlength="6" min="0" max="999999" class="box">

      <input type="submit" value="Save Address" name="submit" class="btn">
   </form>
</section>

<!-- update_address section ends  -->

<!-- footer section starts -->
<?php include 'components/footer.php' ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
