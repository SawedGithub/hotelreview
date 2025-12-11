<?php
	include('Welcome.php');
    $page_title = 'Submit Review';
	require_once ('mysqli_connect.php');
	$_SESSION['hide_file'] = true;
	function findhotel_id($hn,$dbc) {

		// Find hotel id from hotels database
        $qh = "SELECT hotel_id FROM hotels WHERE hotel_name = '$hn'";		
		$hresult = mysqli_query ($dbc, $qh); 
        if ($hresult && mysqli_num_rows($hresult) == 1) {

            // Fetch the one matching row
            $row = mysqli_fetch_assoc($hresult);

            // Store values in variables
            $hid  = $row['hotel_id'];   // hotel id
			return $hid;
        } else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not submit a review due to a system error. We apologize for any inconvenience.</p>'; 
			
			return null;	
		}
		
	}
?>

<h2>Available Hotels</h2>
<h2>Submit a hotel review</h2>

<?php


	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	

		$errors = array(); // Initialize an error array.
		
		$hid = mysqli_real_escape_string($dbc, trim($_POST['hotel_id']));
		
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
			
			// Register the rating in the database...
			
			// Make the query:
			$qr = "INSERT INTO reviews (user_id, hotel_id, rating, description, creation_date) VALUES ('$uid', '$hid', '$hr', '$d', NOW() )";		
			$result = mysqli_query ($dbc, $qr); // Run the query.
			if ($result) { // If it ran OK.
			
				// Print a message:
				echo '<h1>Thank you!</h1>
				<h2>You have submitted a review</h2><p><br /></p>';	
			
			} else { // If it did not run OK.
				
				// Public message:
				echo '<h1>System Error</h1>
				<p class="error">You could not submit a review. We apologize for any inconvenience.</p>'; 
				
				// Debugging message:
				if (mysqli_errno($dbc) == 1062) {
					echo "<p class='error'>You have already reviewed this hotel.<br>Please edit your previous review if you wish to change the rating or description</p>";
				} else {
					echo "<p class='error'>An error occurred. Please try again later.</p>";
				}
							
			} // End of if ($r) IF.
			

			
			
		} else { // Report the errors.
		
			echo '<h1>Error!</h1>
			<p class="error">The following error(s) occurred:<br />';
			foreach ($errors as $msg) { // Print each error.
				echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><p><br /></p>';
			
		} // End of if (empty($errors)) IF.
	
    
	}




	$q = "SELECT hotel_id, hotel_name,address , city, country FROM hotels ORDER BY country ASC";
	$result = mysqli_query($dbc, $q);

	if (!$result) {
		echo "Database Error: " . mysqli_error($dbc);
	}
	
	// Display hotels
	if (mysqli_num_rows($result) > 0) {
		echo "<p>";
		
		while ($row = mysqli_fetch_assoc($result)) {

			$hid = $row['hotel_id'];	
			echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
            echo "<strong>Hotel name:</strong> " . htmlspecialchars($row['hotel_name']) . "<br>";
            echo "<strong>Address:</strong> " . htmlspecialchars($row['address']) . "<br>";
            echo "<strong>City:</strong> " . htmlspecialchars($row['city']) . "<br>";
            echo "<strong>Country:</strong> " . htmlspecialchars($row['country']) . "<br>";

			echo "<button onclick='toggleForm($hid)'>Review this hotel</button>";

			// Hidden form (initially hidden)
        	echo "<div id='form-$hid' style='display:none; margin-top:10px;'>
                <form action='submit.php' method='post'>
                    <input type='hidden' name='hotel_id' value='$hid'>
                    <label>Rating (1-5):</label><br>
                    <input type='number' name='rating' min='1' max='5' value='' required><br><br>
                    <label>Description:</label><br>
                    <textarea name='description' rows='4' cols='50' required></textarea><br><br>
                    <input type='submit' value='Submit Review'>
                </form>
              </div>";

        	echo "</div>";

		}

		echo "</p>";

	} else {
		echo "<p>No hotels found.</p>";
	}

?>



<script>
function toggleForm(hotelID) {
    var formDiv = document.getElementById('form-' + hotelID);
    if (formDiv.style.display === 'none') {
        formDiv.style.display = 'block';
    } else {
        formDiv.style.display = 'none';
    }
}
</script>