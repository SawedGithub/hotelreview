<?php
    include('session.php');
    $page_title = 'Welcome';

?>



    <h1><?php echo "Welcome $un ! "; ?></h1>
    <h3><?php echo "User ID: $uid"; ?></h3>
    
    <h3><a href = "logout.php">Sign Out</a></h3>
   
    <p>What would you like to do?</p><br>

    <ul>
        <li>Submit a hotel review</li><br><br>
        <li>Edit my hotel review</li><br><br>
        <li>View hotel reviews</li><br><br>
    </ul>


    
