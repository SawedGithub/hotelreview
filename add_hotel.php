<?php 
// Include session check and header
include('session.php'); 
$page_title = 'Add New Hotel';
include ('welcome.php'); 
require_once ('mysqli_connect.php');

// --- Start of POST handler for form submission ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    // 1. Check for hotel name
    if (empty($_POST['hotel_name'])) {
        $errors[] = 'You forgot to enter the hotel name.';
    } else {
        $hn = mysqli_real_escape_string($dbc, trim($_POST['hotel_name']));
    }

    // 2. Check for address
    if (empty($_POST['address'])) {
        $errors[] = 'You forgot to enter the address.';
    } else {
        $a = mysqli_real_escape_string($dbc, trim($_POST['address']));
    }
    
    // 3. Check for city
    if (empty($_POST['city'])) {
        $errors[] = 'You forgot to enter the city.';
    } else {
        $c = mysqli_real_escape_string($dbc, trim($_POST['city']));
    }

    // 4. Check for country
    if (empty($_POST['country'])) {
        $errors[] = 'You forgot to enter the country.';
    } else {
        $co = mysqli_real_escape_string($dbc, trim($_POST['country']));
    }

    if (empty($errors)) { // If everything's OK.
        
        // Insert the new hotel into the 'hotels' table
        $q = "INSERT INTO hotels (hotel_name, address, city, country) 
              VALUES ('$hn', '$a', '$c', '$co')";
        
        $result = mysqli_query($dbc, $q); 

        if ($result) { // If it ran OK.
            echo '<h1>Hotel Added!</h1><p>The hotel **' . htmlspecialchars($hn) . '** has been successfully added to the system.</p>';
            echo '<p><a rel="noopener" href="submit.php">You can now submit a review for this hotel.</a></p>';
            
            // Optionally redirect to reviews.php or clear the form (we'll display a success message and keep the page open)
            // header("Location: reviews.php");
            // exit();
            
        } else { // If it did not run OK.
            echo '<h1>System Error</h1>
            <p class="error">The hotel could not be added due to a system error. We apologize for any inconvenience.</p>';
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

    mysqli_close($dbc); 
}
// --- End of POST handler ---
?>

<h1>Add a New Hotel</h1>

<form action="add_hotel.php" method="post">
    
    <label for="hotel_name">Hotel Name:</label>
    <input type="text" name="hotel_name" size="40" maxlength="100" required value="<?php if (isset($_POST['hotel_name'])) echo htmlspecialchars($_POST['hotel_name']); ?>" />
    
    <label for="address">Address:</label>
    <input type="text" name="address" size="40" maxlength="255" required value="<?php if (isset($_POST['address'])) echo htmlspecialchars($_POST['address']); ?>" />

    <label for="city">City:</label>
    <input type="text" name="city" size="30" maxlength="50" required value="<?php if (isset($_POST['city'])) echo htmlspecialchars($_POST['city']); ?>" />

    <label for="country">Country:</label>
    <input type="text" name="country" size="30" maxlength="50" required value="<?php if (isset($_POST['country'])) echo htmlspecialchars($_POST['country']); ?>" />

    <input type="submit" name="submit" value="Add Hotel" />
</form>

</div> </body>
</html>