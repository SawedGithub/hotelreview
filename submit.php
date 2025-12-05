<?php
    include('session.php');
    $page_title = 'Submit Review';

?>

<h1><?php echo "Welcome $un ! "; ?></h1>
<h3><?php echo "User ID: $uid"; ?></h3>

<h3><a rel="noopener" href = "welcome.php">Return to home page</a></h3>
<p>Submit a hotel review</p><br>

<form action="submit.php" method="post">
    <label for="hotel_name">Hotel Name:</label><br>
    <input type="text" id="hotel_name" name="hotel_name" required><br><br>

    <label for="address">Hotel Address:</label><br>
    <textarea id="address" name="address" rows="4" cols="50" required></textarea><br><br>

    <label for="rating">Rating (1-5):</label><br>
    <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

    <input type="submit" value="Submit Review">