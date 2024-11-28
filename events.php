<?php
include 'components/connect.php'; 

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Café</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- font awesome cdn links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <?php include 'components/user_header.php'; ?>

    <div class="heading">
        <h3>events</h3>
        <p> <span>Events /<a href="home.php"> Home</a></span></p>
    </div>

    <section class="event-home">
        <div class="event-content">
            <h2 class="tittle">Flavors and Fun: A Culinary Adventure</h2>
        </div>

        <div class="swiper mySwiper event-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="images/slide-1.jpg" alt=""></div>
                <div class="swiper-slide"><img src="images/slide-2.jpg" alt=""></div>
                <div class="swiper-slide"><img src="images/slide-3.jpg" alt=""></div>
                <div class="swiper-slide"><img src="images/slide-4.jpg" alt=""></div>
                <div class="swiper-slide"><img src="images/slide-5.jpg" alt=""></div>
                <div class="swiper-slide"><img src="images/slide-6.jpg" alt=""></div>
            </div>
        </div>
        <a href="contact.php" class="btn">Contact Us</a>
    </section>

    <section class="event-service">
        <h1 class="tittle"> Our <span>Events</span></h1>
        <div class="box-container">
            <?php
            $select_events = $conn->prepare("SELECT * FROM events");
            $select_events->execute([]);
            if ($select_events->rowCount() > 0) {
                while ($fetch_events = $select_events->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <div class="box">
                        <div class="price">Rs.<?= $fetch_events['price']; ?>/-</div>
                        <div class="name"><?= $fetch_events['name']; ?></div>
                        <div class="details"><?= $fetch_events['details']; ?></div>
                        <div class="flex-btn">
                            <a href="contact.php" class="btn">Contact Us</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">No Events Added Yet!</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>

    <script>
        var swiper = new Swiper(".event-slider", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 2,
                slideShadows: true,
            },
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });
    </script>
</body>
</html>
