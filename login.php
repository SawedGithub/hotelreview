
<?php

$page_title = 'Login';
include ('header.html');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('mysqli_connect.php');

	$errors = array();

    if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}

    if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}

    if (empty($errors)) {

        $q = "SELECT user_id, user_name FROM users WHERE email = '$e' AND pass = SHA1('$p')";		
		$result = mysqli_query ($dbc, $q); 
        if ($result) {

            // Fetch the one matching row
            $row = mysqli_fetch_assoc($result);

            // Store values in variables
            $un  = $row['user_name'];   // username
            echo "<h2>Logged in as: $un </h2>";

            $_SESSION['login_user'] = $un;
            header("location: welcome.php");
        }
        else {
            echo "Invalid login";

        }    
    
        
    mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		exit();
		
	} else { // Report the errors.
	
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.
	
	mysqli_close($dbc); // Close the database connection.
}



?>
<h1> Log into your account</h1>
<form action="login.php" method="post">

	<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>

	<p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>"  /></p>

	<p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>"  /></p>

	<p><input type="submit" name="submit" value="Login" /></p>
</form>