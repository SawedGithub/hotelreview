<?php 
// Start the session.
if (!isset($_SESSION)) {
    session_start();
}

$page_title = 'Login';
// Includes the HTML header and links to styles.css
include ('header.html'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once ('mysqli_connect.php'); // Connect to the database.

    $errors = array(); // Initialize an error array.

    // 1. Check for email address:
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }

    // 2. Check for passwords and enforce that they match
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            // Use the first password input for the database check
            $p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'You forgot to enter your password.';
    }
    
    if (empty($errors)) {

        // Query: find user with matching email and SHA1-hashed password
        // $p contains the password from pass1 (which matched pass2)
        $q = "SELECT user_id, user_name FROM users WHERE email = '$e' AND pass = SHA1('$p')";
        $result = mysqli_query($dbc, $q);

        // Check if query ran AND found exactly 1 row
        if ($result && mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);

            // FIX: Removed invisible non-breaking spaces (was: $un  = ...)
            $un = $row['user_name'];
            $uid = $row['user_id'];

            echo "<h2>Logged in as: $un </h2>";

            // FIX: Uses standard session variable names (was: $_SESSION['login_user']    = ...)
            $_SESSION['user_name'] = $un; 
            $_SESSION['user_id'] = $uid; 

            // FIX: Redirects to home.php (was: header("location: welcome.php");)
            header("Location: welcome.php");
            mysqli_free_result($result);
            mysqli_close($dbc); 
            exit();

        } else {
            // Login failed
            echo '<h1>Error!</h1>
            <p class="error">Invalid email or password. Please try again.</p>';
        }

    } else {

        // Other errors (missing fields, password mismatch, etc.)
        echo '<h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br />';

        foreach ($errors as $msg) {
            echo " - $msg<br />\n";
        }

        echo '</p><p>Please try again.</p>';
    }

    mysqli_close($dbc); // Close the database connection.
    // The previous code had a redundant exit() here which is removed, 
    // as the successful path already has one and the error path falls through.
}

?>

<h1>Log into your account</h1>
<form action="login.php" method="post">

    <p>Email Address: <input type="text" name="email" size="20" maxlength="60" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>

    <p>Password: <input type="password" name="pass1" size="10" maxlength="20" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" /></p>

    <p>Confirm Password: <input type="password" name="pass2" size="10" maxlength="20" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" /></p>

    <p><input type="submit" name="submit" value="Login" /></p>

    <a rel="noopener" href="password.php">Forgot password?</a>
</form>

</div> </body>
</html>