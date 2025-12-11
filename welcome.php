<?php
    include('session.php');
    $page_title = 'Welcome';

?>



    <h1><?php echo "Welcome $un ! "; ?></h1>
    
    <h3><a rel="noopener" href = "logout.php">Sign Out</a></h3>
   
    <p>What would you like to do?</p><br>

    <ul>
        <li><a rel="noopener" href="submit.php?hide_file=1">Submit a hotel review</a></li><br><br>
        <li><a rel="noopener" href="edit_review.php?hide_file=1">Edit my hotel review</a></li><br><br>
        <li><a rel="noopener" href="reviews.php?hide_file=1">View hotel reviews</a></li><br><br>
    </ul>




    
