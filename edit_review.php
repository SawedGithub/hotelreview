<?php
	include('Welcome.php');
    $page_title = 'Submit Review';
    require ('mysqli_connect.php');
    $_SESSION['hide_file'] = true;
?>

<h2>Edit your hotel reviews</h2><br>

<?php

    
    function findhotel_name($hid,$dbc) {

        $hotel_id = (int)$hid;

        $q = "SELECT hotel_name FROM hotels WHERE hotel_id = $hotel_id LIMIT 1";
        $result = mysqli_query($dbc, $q);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['hotel_name'];
        } else {
            return null; // hotel not found
        }
		
	}

     // your mysqli connection file
    
    // Prepared statement
    $q = "SELECT review_id, hotel_id, rating, description, creation_date 
        FROM reviews 
        WHERE user_id = $uid 
        ORDER BY creation_date DESC";

    $result = mysqli_query($dbc, $q);

    if (!$result) {
        echo "Database Error: " . mysqli_error($dbc);
        exit;
    }
    
    // Display reviews
    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $hn = findhotel_name($row['hotel_id'],$dbc);
            echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
            echo "<strong>Hotel:</strong> " . htmlspecialchars($hn) . "<br>";
            echo "<strong>Rating:</strong> " . $row['rating'] . "/5<br>";
            echo "<strong>Description:</strong> " . htmlspecialchars($row['description']) . "<br>";
            echo "<strong>Date:</strong> " . $row['creation_date'] . "<br>";
            
            $review_id = $row['review_id'];
            echo "<button onclick='toggleForm($review_id)'>Edit</button>";
            echo "</div>";

        // Hidden form (initially hidden)
            echo "<div id='form-$review_id' style='display:none; margin-top:10px;'>
                <form action='edit_review.php' method='post'>
                    <input type='hidden' name='review_id' value='$review_id'>
                    <label>Rating (1-5):</label><br>
                    <input type='number' name='rating' min='1' max='5' value='{$row['rating']}' required><br><br>
                    <label>Description:</label><br>
                    <textarea name='description' rows='4' cols='50' required>" . htmlspecialchars($row['description']) . "</textarea><br><br>
                    <input type='submit' value='Update Review'>
                </form>
              </div>";

            echo "</div>";
        }

    } else {
        echo "<p>You have not written any reviews yet.</p>";
    }
?>



<script>
function toggleForm(reviewId) {
    var formDiv = document.getElementById('form-' + reviewId);
    if (formDiv.style.display === 'none') {
        formDiv.style.display = 'block';
    } else {
        formDiv.style.display = 'none';
    }
}
</script>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $errors = array(); // Initialize an error array.
	
        // Check for a user name:
        if (empty($_POST['description'])) {
            $errors[] = 'You forgot to enter review description.';
        } else {
            $d = mysqli_real_escape_string($dbc, trim($_POST['description']));
        }
        
        if (empty($_POST['rating']) || !is_numeric($_POST['rating']) || $_POST['rating'] < 1 || $_POST['rating'] > 5) {
            $errors[] = 'You must enter a valid rating between 1 and 5.';
        } else {
            $hr = mysqli_real_escape_string($dbc, trim($_POST['rating']));
        }

        if (empty($errors)) { // If everything's OK.
		
		
		    // Make the query:
		    $qr = "UPDATE reviews 
                   SET rating = '$hr', description = '$d', creation_date = NOW() 
                   WHERE review_id = {$_POST['review_id']} AND user_id = $uid";		
		    $result = mysqli_query ($dbc, $qr); // Run the query.
		    if ($result) { // If it ran OK.
		
			// Print a message:
			    header("location: edit_review.php"); // replace with your page showing reviews
                exit;
		
		    } else { // If it did not run OK.
			
			// Public message:
			    echo '<h1>System Error</h1>
			    <p class="error">You could not submit a review. We apologize for any inconvenience.</p>'; 
			
						
		    } // End of if ($r) IF.
		
        } else { // Report the errors.
        
            echo '<h1>Error!</h1>
            <p class="error">The following error(s) occurred:<br />';
            foreach ($errors as $msg) { // Print each error.
		    	echo " - $msg<br />\n";
		    }
		    echo '</p><p>Please try again.</p><p><br /></p>';
		
	    }
    }

    

?>