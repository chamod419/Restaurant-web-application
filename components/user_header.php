<?php
// staff_header.php
include 'connect.php';

if (!isset($conn)) {
    die('Database connection not established.');
}

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>

<header class="header"> 

   <section class="flex">

      <a href="home.php" class="logo">The Gallery Café</a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="menu.php">Menu</a>
         <a href="reservations.php">Reservations</a>
         <a href="pre-order.php">Pre-Order</a>
         <a href="events.php">Events</a>
         <a href="about.php">About</a>
         <a href="contact.php">Contact</a>
      </nav>

      <div class="icons">
         <?php
            $count_user_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_user_cart_items->execute([$user_id]);
            $total_user_cart_items = $count_user_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_user_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>
      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <P class="name"><?= $fetch_profile['name']; ?></P>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="components/user_logout.php" onclick="return comfirm('logout from this website?');" class="delete-btn">logout</a>
         </div>
         <p class="account"><a href="register.php">register</a> or <a href="login.php">login</a></p>
         <?php
         }
         else{
         ?>
            <p class="name">Please Login First</p>
            <a href="login.php" class="btn">login</a>
         <?php 
         }
         ?>
           
      </div>

</section>
</header>
