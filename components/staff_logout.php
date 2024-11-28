<?php
// staff_logout.php
session_start();
session_unset();
session_destroy();
header('Location: ../staff/staff_login.php');
exit();
?>
