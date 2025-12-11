<?php
    include('Welcome.php');
    $page_title = 'Hotel Reviews';
    require_once ('mysqli_connect.php');
    $_SESSION['hide_file'] = true;
?>


<h2>Available Hotels</h2>

<?php 

	$q = "SELECT hotel_id, hotel_name,address , city, country FROM hotels ORDER BY hotel_id ASC";
	$result = mysqli_query($dbc, $q);

	if (!$result) {
		echo "Database Error: " . mysqli_error($dbc);
	}
	
	// Display hotels
	if (mysqli_num_rows($result) > 0) {
		echo "<p>";
		
		while ($row = mysqli_fetch_assoc($result)) {

            $hotel_id = $row['hotel_id'];
			echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
            echo "<strong>Hotel name:</strong> " . htmlspecialchars($row['hotel_name']) . "<br>";
            echo "<strong>Address:</strong> " . htmlspecialchars($row['address']) . "<br>";
            echo "<strong>City:</strong> " . htmlspecialchars($row['city']) . "<br>";
            echo "<strong>Country:</strong> " . htmlspecialchars($row['country']) . "<br>";

            echo "<button onclick='toggleReviews($hotel_id)'>View reviews</button>";
            echo "<div id='reviews-$hotel_id' style='display:none; margin-top:10px;'>";

            $rQuery = "SELECT users.user_name, reviews.rating, reviews.description, reviews.creation_date
               FROM reviews
               JOIN users ON reviews.user_id = users.user_id
               WHERE reviews.hotel_id = $hotel_id
               ORDER BY reviews.creation_date DESC";

            $rResult = mysqli_query($dbc, $rQuery);

            if ($rResult && mysqli_num_rows($rResult) > 0) {
                echo "<table border='1' cellpadding='5' cellspacing='0'>";
                echo "<tr><th>User</th><th>Rating</th><th>Description</th><th>Date</th></tr>";
                while ($rRow = mysqli_fetch_assoc($rResult)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($rRow['user_name']) . "</td>";
                    echo "<td>" . $rRow['rating'] . "/5</td>";
                    echo "<td>" . htmlspecialchars($rRow['description']) . "</td>";
                    echo "<td>" . $rRow['creation_date'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No reviews yet for this hotel.</p>";
            }

            echo "<button onclick='showAverageRating($hotel_id)'>Show Average Rating</button>";
            echo "<p id='avg-$hotel_id'></p>"; // Placeholder for average rating

            echo "</div>"; // End reviews div
            echo "</div>"; // End hotel div
		}

		echo "</p>";

	} else {
		echo "<p>No hotels found.</p>";
	}

?>

<script>
function toggleReviews(hotelId) {
    var div = document.getElementById('reviews-' + hotelId);
    if (div.style.display === 'none') {
        div.style.display = 'block';
    } else {
        div.style.display = 'none';
    }
}
</script>

<script>
function showAverageRating(hotelId) {
    fetch('get_avg_rating.php?hotel_id=' + hotelId)
    .then(response => response.json())
    .then(data => {
        if (data.avg !== null) {
            document.getElementById('avg-' + hotelId).innerText = 'Average Rating: ' + data.avg + ' / 5';
        } else {
            document.getElementById('avg-' + hotelId).innerText = 'No reviews yet.';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>