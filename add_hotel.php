<?php 
// Include session check and header
include('session.php'); 
$page_title = 'Add New Hotel';
include('welcome.php'); 
require_once('mysqli_connect.php');

// --- POST handler for form submission ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    // 1. Hotel name
    if (empty($_POST['hotel_name'])) {
        $errors[] = 'You forgot to enter the hotel name.';
    } else {
        $hn = mysqli_real_escape_string($dbc, trim($_POST['hotel_name']));
    }

    // 2. Address
    if (empty($_POST['address'])) {
        $errors[] = 'You forgot to enter the address.';
    } else {
        $a = mysqli_real_escape_string($dbc, trim($_POST['address']));
    }

    // 3. City
    if (empty($_POST['city'])) {
        $errors[] = 'You forgot to enter the city.';
    } else {
        $c = mysqli_real_escape_string($dbc, trim($_POST['city']));
    }

    // 4. Country
    if (empty($_POST['country'])) {
        $errors[] = 'You forgot to enter the country.';
    } else {
        $co = mysqli_real_escape_string($dbc, trim($_POST['country']));
    }

    // 5. Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetmp = $_FILES['image']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Unique filename to prevent overwriting
            $new_filename = uniqid('hotel_', true) . '.' . $ext;
            $destination = 'hotelimg/' . $new_filename;

            if (move_uploaded_file($filetmp, $destination)) {
                $img = mysqli_real_escape_string($dbc, $new_filename);
            } else {
                $errors[] = 'Failed to move uploaded file.';
            }
        } else {
            $errors[] = 'Invalid file type. Only JPG, PNG, GIF allowed.';
        }
    } else {
        $errors[] = 'Please upload a hotel image.';
    }

    // If no errors, insert into DB
    if (empty($errors)) {
        $q = "INSERT INTO hotels (hotel_name, address, city, country, image) 
              VALUES ('$hn', '$a', '$c', '$co', '$img')";
        $result = mysqli_query($dbc, $q);

        if ($result) {
            echo '<h1>Hotel Added!</h1>';
            echo '<p>The hotel <strong>' . htmlspecialchars($hn) . '</strong> has been successfully added.</p>';
            echo '<p><a href="submit.php">You can now submit a review for this hotel.</a></p>';
        } else {
            echo '<h1>System Error</h1>
            <p class="error">The hotel could not be added due to a system error.</p>';
            echo '<p>' . mysqli_error($dbc) . '</p>';
        }
    } else {
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) {
            echo " - $msg<br>\n";
        }
        echo '</p><p>Please try again.</p>';
    }

    mysqli_close($dbc); 
}
?>

<h1>Add a New Hotel</h1>

<form action="add_hotel.php" method="post" enctype="multipart/form-data">
    
    <label for="hotel_name">Hotel Name:</label>
    <input type="text" name="hotel_name" size="40" maxlength="100" required 
        value="<?php if (isset($_POST['hotel_name'])) echo htmlspecialchars($_POST['hotel_name']); ?>" />

    <label for="address">Address:</label>
    <input type="text" name="address" size="40" maxlength="255" required 
        value="<?php if (isset($_POST['address'])) echo htmlspecialchars($_POST['address']); ?>" />

    <label for="city">City:</label>
    <input type="text" name="city" size="30" maxlength="50" required 
        value="<?php if (isset($_POST['city'])) echo htmlspecialchars($_POST['city']); ?>" />

    <label for="country">Country:</label>
    <input type="text" name="country" size="30" maxlength="50" required 
        value="<?php if (isset($_POST['country'])) echo htmlspecialchars($_POST['country']); ?>" />

    <label for="image">Hotel Image:</label>
    <input type="file" name="image" accept="image/*" required />

    <input type="submit" name="submit" value="Add Hotel" />
</form>

</div> 
</body>
</html>
