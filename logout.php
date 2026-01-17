<?php
session_start();
session_destroy();
setcookie('user_id', '', time() - 3600, "/");
setcookie('username', '', time() - 3600, "/");
echo "<script>alert('Anda telah logout.'); window.location='login.php';</script>";
exit;
?>