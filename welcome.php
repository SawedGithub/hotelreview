<?php
    include('session.php');
    $page_title = 'Welcome';

?>



   <h1>Welcome <?php echo $login_session; ?></h1> 
   <h2><a href = "logout.php">Sign Out</a></h2>
