<?php
 
include 'components/connect.php'; 

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
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
   <title>Home</title>

 <!-- font awesome cdn link -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
 <!--coustom css file link -->
 
 <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- header section starts -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<!-- home section stats -->

<section class="home">
            
   <div class="swiper home-slider">

      <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="content">
            <span>Whellcome To The</span>
            <h3>Gallery Café</h3>
            <a href="home.php" class="btn">see home</a>
         </div>
         <div class="image">
            <img src="images/antique-cafe-bg-01.jpg" alt="">
         </div>
      </div> 

      <div class="swiper-slide slide">
         <div class="content">
            <span>pre-order online</span>
            <h3>delicious food</h3>
            <a href="menu.php" class="btn">see menus</a> 
         </div>
         <div class="image">
            <img src="images/delicious food.jpg" alt="">
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="content">
            <span>reserve online</span>
            <h3>reserve your table</h3>
            <a href="reservations.php" class="btn">see reservations</a>
         </div>
         <div class="image">
            <img src="images/cafe_table.jpg" alt="">
         </div>
      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<!-- home section ends -->

<!-- home category section starts -->

<section class="home-category">

  <h1 class="tittle">food category</h1>

  <div class="box-container">

     <a href="category.php?category=Sri Lankan food" class="box">
        <img src="images/Sri Lankan food.jpg" alt="">
        <h3>Sri Lankan food</h3>
     </a>

     <a href="category.php?category=Chinese food" class="box">
        <img src="images/Chinese food.jpg" alt="">
        <h3>Chinese food</h3>
     </a>

     <a href="category.php?category=Italian food" class="box">
        <img src="images/Italian food.jpg" alt="">
        <h3>Italian food</h3>
     </a>

     <a href="category.php?category=Indian food" class="box">
        <img src="images/Indian food.jpg" alt="">
        <h3>Indian food</h3>
     </a>

  </div>

</section>

<!-- home category section ends -->



<!-- home products section starts -->

<section class="products">

   <h1 class="tittle">Hot Choices</h1>

   <div class="box-container">

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><span>Rs.</span><?= $fetch_products['price']; ?>/-</div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
      }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="menu.php" class="btn">loard more</a>
   </div>

</section>

<!-- home products section ends -->


<!-- home Special food promotions section starts -->

<section class="products">

   <h1 class="tittle">Special food & promotions</h1>

   <div class="box-container">

      <?php
         $select_promotions = $conn->prepare("SELECT * FROM `products` WHERE `promotion` = 1 LIMIT 6");
         $select_promotions->execute();
         if($select_promotions->rowCount() > 0){
            while($fetch_promotions = $select_promotions->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_promotions['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_promotions['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_promotions['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_promotions['image']; ?>">
         <a href="quick_view.php?pid=<?= $fetch_promotions['id']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_promotions['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_promotions['category']; ?>" class="cat"><?= $fetch_promotions['category']; ?></a>
         <div class="name"><?= $fetch_promotions['name']; ?></div>
         <div class="flex">
            <div class="price"><span>Rs.</span><?= $fetch_promotions['price']; ?>/-</div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
      }
         }else{
            echo '<p class="empty">no promotions available at the moment!</p>';
         }
      ?>

   </div>

   

</section>

<!-- home Special food promotions section ends -->

<!-- footer section starts -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- custom js file link -->
<script src="js/script.js"></script>

<script>
var swiper = new Swiper(".home-slider", {
   effect: "flip",
   grabCursor: true,
   loop:true,
   pagination: {
      clickable:true,
      el: ".swiper-pagination",
   },
});
</script>

</body>
</html>
