<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_staff = $conn->prepare("DELETE FROM `staff` WHERE id = ?");
   $delete_staff->execute([$delete_id]);
   header('location:staff_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Staff Accounts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- staff accounts section starts  -->

<section class="accounts">

   <h1 class="heading">Staff Accounts</h1>

   <div class="box-container">

  

   <?php
      $select_account = $conn->prepare("SELECT * FROM `staff`");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <p> Staff ID: <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username: <span><?= $fetch_accounts['name']; ?></span> </p>
      <div class="flex-btn">
         <a href="staff_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this staff account?');">Delete</a>
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_staff.php?id='.$fetch_accounts['id'].'" class="option-btn">Update</a>';
            }
         ?>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No staff accounts available</p>';
   }
   ?>

   </div>

</section>

<!-- staff accounts section ends -->

<?php include '../components/staff_footer.php'; ?>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
