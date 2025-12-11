<?php

    $page_title = 'Hotel Reviewer';
    include ('header.html');

    echo '<h2>Available Hotels</h2>';
    require ('mysqli_connect.php');

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
        	echo "</div>";

		}

		echo "</p>";

    }else {
		echo "<p>No hotels found.</p>";
	}


?>

