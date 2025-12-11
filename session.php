<?php
   // Start the session
   session_start();

   if(!isset($_SESSION['login_user'])&&!isset($_SESSION['login_userid'])){
      header("location: login.php");
      die();
   }
   $un = $_SESSION['login_user'];
   $uid = $_SESSION['login_userid'];


   if (isset($_GET['hide_file'])) {
    $_SESSION['hide_file'] = true;
}
   else {
    $_SESSION['hide_file'] = false;
   }
?>