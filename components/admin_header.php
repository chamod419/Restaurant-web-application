<?php
// Ensure $admin_id is set and valid. For example, you might set it from a session variable:
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    // Handle the case where admin_id is not set, e.g., redirect to login page
    header("Location: admin_login.php");
    exit;
}

if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>
<!-- custom css file link  -->
<link rel="stylesheet" href="../css/admin_style.css">
<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="dashboard.php">Dashboard</a>
         <a href="products.php">Meals</a>
         <a href="placed_orders.php">Pre-Orders</a>
         <a href="admin_events.php">Events</a>
         <a href="staff_tables.php">Tables</a>
         <a href="messages.php">Messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

            if ($fetch_profile) {
                echo '<p>' . htmlspecialchars($fetch_profile['name']) . '</p>';
            } else {
                echo '<p>Profile not found</p>';
            }
         ?>
         <a href="update_profile.php" class="btn">update profile</a>
         <div class="flex-btn">
            <a href="../admin/admin_login.php" class="option-btn">login</a>
            <a href="register_admin.php" class="option-btn">register</a>
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
      </div>

   </section>

</header>
