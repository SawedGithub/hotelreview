<?php 
// Include session check and header
include('session.php'); 
$page_title = 'Submit Review';
include ('welcome.php'); 
require_once ('mysqli_connect.php'); 

// --- Start of POST handler for form submission ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    // 1. Check for hotel ID selection
    if (empty($_POST['hotel_id']) || !is_numeric($_POST['hotel_id'])) {
        $errors[] = 'You must select a hotel.';
    } else {
        $hid = mysqli_real_escape_string($dbc, trim($_POST['hotel_id']));
    }

    // 2. Check for rating
    if (empty($_POST['rating']) || !is_numeric($_POST['rating']) || $_POST['rating'] < 1 || $_POST['rating'] > 5) {
        $errors[] = 'You must enter a valid rating between 1 and 5.';
    } else {
        $hr = mysqli_real_escape_string($dbc, trim($_POST['rating']));
    }

    // 3. Check for review text (using the database column name: 'description')
    if (empty($_POST['description'])) {
        $errors[] = 'You forgot to enter the review description.';
    } else {
        $d = mysqli_real_escape_string($dbc, trim($_POST['description']));
    }

    if (empty($errors)) { // If everything's OK.
        
        // Insert the new review into the 'reviews' table
        $q = "INSERT INTO reviews (user_id, hotel_id, rating, description, creation_date) 
              VALUES ($uid, $hid, '$hr', '$d', NOW())";
        
        $result = mysqli_query($dbc, $q); 

        if ($result) { // If it ran OK.
            // Redirect to the review list page
            echo '<h1>Review Submitted!</h1><p>Thank you for submitting your review!</p>';
            header("Location: submit.php");
            exit();
        } else { // If it did not run OK.
            echo '<h1>System Error</h1>
            <p class="error">The review could not be submitted due to a system error. We apologize for any inconvenience.</p>';
            echo '<p>' . mysqli_error($dbc) . '</p>'; // Helpful for debugging
        }

    } else { // Report the errors.
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) {
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
    }

    
}
// --- End of POST handler ---
?>

<h2>Submit a Hotel Review</h2>

<p>
    Can't find the hotel you are looking for? 
    <a rel="noopener" href="add_hotel.php" style="font-weight: bold;">Click here to add a new hotel.</a>
</p>

<?php
// Start of the Form Display Logic
require ('mysqli_connect.php');

// Retrieve all hotels for the dropdown menu
$q = "SELECT hotel_id, hotel_name, city, country FROM hotels ORDER BY hotel_name ASC";
$result = mysqli_query($dbc, $q);

if ($result && mysqli_num_rows($result) > 0) {
    // Hotels found: Display the form
    ?>
    <form action="submit.php" method="post">
        
        <label for="hotel_id">Select Hotel:</label>
        <select name="hotel_id" required>
            <option value="">-- Select One --</option>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['hotel_id']}'>{$row['hotel_name']} ({$row['city']}, {$row['country']})</option>";
            }
            ?>
        </select>
        
        <label for="rating">Rating (1-5):</label>
        <input type="number" name="rating" min="1" max="5" required />

        <label for="description">Your Review:</label>
        <textarea name="description" rows="6" required></textarea>

        <input type="submit" name="submit" value="Submit Review" />
    </form>

    <?php
} else {
    // No hotels found: Display the error message AND the link to add a new hotel
    echo "<p class='error'>No hotels found in the database. Please add a hotel first.</p>";
    // The link is already shown above, but you could repeat it here if preferred.
}

mysqli_close($dbc); 
?>

</div> </body>
</html>