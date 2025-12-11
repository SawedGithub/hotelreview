
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

		// Query: find user with matching email and password
		$q = "SELECT user_id, user_name FROM users WHERE email = '$e' AND pass = SHA1('$p')";
		$result = mysqli_query($dbc, $q);

		// Check if query ran AND found exactly 1 row
		if ($result && mysqli_num_rows($result) == 1) {

			$row = mysqli_fetch_assoc($result);

			$un  = $row['user_name'];
			$uid = $row['user_id'];

			echo "<h2>Logged in as: $un </h2>";

			$_SESSION['login_user']    = $un;
			$_SESSION['login_userid']  = $uid;

			header("location: welcome.php");
			exit();

		} else {

			// Login failed
			echo '<h1>Error!</h1>
			<p class="error">Invalid email or password. Please try again.</p>';
		}

	} else {

		// Other errors (missing fields, etc.)
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';

		foreach ($errors as $msg) {
			echo " - $msg<br />\n";
		}

		echo '</p><p>Please try again.</p>';
	}

		
		mysqli_close($dbc); // Close the database connection.

			// quit the script:
		exit();
	}

?>

<h1> Log into your account</h1>
<form action="login.php" method="post">

	<p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>

	<p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>"  /></p>

	<p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>"  /></p>

	<p><input type="submit" name="submit" value="Login" /></p>

	<a rel="noopener" href="password.php">Forgot password?</a>
</form>