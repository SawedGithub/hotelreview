<?php
    // Include the file that handles session check and sets $un, $uid
    include('session.php'); 
    $page_title = 'Edit Review';
    
    // Include the HTML header/CSS link now
    include ('welcome.php');
    
    // Welcome content using the $un variable defined in session.php
    
    require_once ('mysqli_connect.php');

    // --- Start of POST handler for form submission ---
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $errors = array(); // Initialize an error array.
    
        // Check for the reviewdescription (using the correct field name 'description')
        if (empty($_POST['description'])) {
            $errors[] = 'You forgot to enter the reviewdescription.';
        } else {
            // Use the form field name 'description'
            $d = mysqli_real_escape_string($dbc, trim($_POST['description']));
        }
        
        // Check for rating
        if (empty($_POST['rating']) || !is_numeric($_POST['rating']) || $_POST['rating'] < 1 || $_POST['rating'] > 5) {
            $errors[] = 'You must enter a valid rating between 1 and 5.';
        } else {
            $hr = mysqli_real_escape_string($dbc, trim($_POST['rating']));
        }

        if (empty($errors)) { // If everything's OK.
        
            // Make the UPDATE query:
            // FIX: Use the confirmed database column name: 'description'
            $qr = "UPDATE reviews 
                    SET rating = '$hr', description = '$d', creation_date = NOW() 
                    WHERE review_id = {$_POST['review_id']} AND user_id = $uid";      
            $result = mysqli_query ($dbc, $qr); // Run the query.

            if ($result) { // If it ran OK.
                // Redirect to refresh the page and show updated review:
                header("location: edit_review.php"); 
                exit;
            } else { // If it did not run OK.
                echo '<h1>System Error</h1>
                <p class="error">You could not update the review. We apologize for any inconvenience.</p>'; 
            }
        
        } else { // Report the errors.
            echo '<h1>Error!</h1>
            <p class="error">The following error(s) occurred:<br />';
            foreach ($errors as $msg) { // Print each error.
                echo " - $msg<br />\n";
            }
            echo '</p><p>Please try again.</p><p><br /></p>';
        }
    }
    // --- End of POST handler ---


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
?>

<h2>Edit Your Hotel Reviews</h2><br>

<?php

    // Prepared statement
    // FIX: Use the confirmed database column name: 'description'
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
            
            // Applied review-card class and data-label span
            echo "<div class='review-card'>";
            echo "<span class='data-label'>Hotel:</span> " . htmlspecialchars($hn) . "<br>";
            echo "<span class='data-label'>Rating:</span> <span class='rating-text'>" . $row['rating'] . "/5</span><br>";
            // FIX: Display the content from 'description'
            echo "<span class='data-label'>Description:</span> " . htmlspecialchars($row['description']) . "<br>";
            echo "<span class='data-label'>Date:</span> " . $row['creation_date'] . "<br>";
            
            $review_id = $row['review_id'];
            // Applied edit-button class
            echo "<button onclick='toggleForm($review_id)' class='edit-button' style='margin-top:10px;'>Edit Review</button>";
            echo "</div>";

        // Hidden form (initially hidden)
            echo "<div id='form-$review_id' style='display:none; margin-top:20px; padding:15px; border: 1px dashed #ccc; border-radius:4px;'>
                <form action='edit_review.php' method='post'>
                    <h3>Update Review for " . htmlspecialchars($hn) . "</h3>
                    <input type='hidden' name='review_id' value='$review_id'>
                    <label>New Rating (1-5):</label>
                    <input type='number' name='rating' min='1' max='5' value='{$row['rating']}' required>
                    
                    <label>Newdescription:</label>
                    <textarea name='description' rows='4' required>" . htmlspecialchars($row['description']) . "</textarea>
                    
                    <input type='submit' value='Update Review'>
                </form>
              </div>";

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


</div> </body>
</html>