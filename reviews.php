<?php
    // Line 2 fix: Removed the incorrect include('Welcome.php')
    include('session.php'); 
    $page_title = 'Hotel Reviews';
    
    // Include the HTML header/CSS link now
    include ('welcome.php');
    
    require_once ('mysqli_connect.php'); // Ensure connection is made only once
    

?>


<h2>View all available Hotel reviews</h2>

<?php 
    // Query to retrieve all hotels
    $q = "SELECT hotel_id, hotel_name, address, city, country FROM hotels ORDER BY hotel_id ASC";
    $result = mysqli_query($dbc, $q);

    if (!$result) {
        echo "Database Error: " . mysqli_error($dbc);
    }
    
    // Display hotels
    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {

            $hotel_id = $row['hotel_id'];
            
            echo "<div class='hotel-card'>"; 
            echo "<span class='data-label'>Hotel name:</span> " . htmlspecialchars($row['hotel_name']) . "<br>";
            echo "<span class='data-label'>Address:</span> " . htmlspecialchars($row['address']) . "<br>";
            echo "<span class='data-label'>City:</span> " . htmlspecialchars($row['city']) . "<br>";
            echo "<span class='data-label'>Country:</span> " . htmlspecialchars($row['country']) . "<br>";

            echo "<button onclick='toggleReviews($hotel_id)' style='margin-top:10px;'>View reviews</button>";
            
            echo "<div id='reviews-$hotel_id' style='display:none; margin-top:15px;'>"; 

            // Query to retrieve reviews for this specific hotel
            $rQuery = "SELECT users.user_name, reviews.rating, reviews.description, reviews.creation_date
                FROM reviews
                JOIN users ON reviews.user_id = users.user_id
                WHERE reviews.hotel_id = $hotel_id
                ORDER BY reviews.creation_date DESC";

            $rResult = mysqli_query($dbc, $rQuery);

            if ($rResult && mysqli_num_rows($rResult) > 0) {
                
                echo "<table class='review-table'>"; 
                echo "<tr><th>User</th><th>Rating</th><th>Description</th><th>Date</th></tr>";
                while ($rRow = mysqli_fetch_assoc($rResult)) {
                    $rating_display = "<span class='rating-text'>" . $rRow['rating'] . "/5</span>";
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($rRow['user_name']) . "</td>";
                    echo "<td>" . $rating_display . "</td>";
                    // Uses the confirmed column name: description
                    echo "<td>" . htmlspecialchars($rRow['description']) . "</td>"; 
                    echo "<td>" . $rRow['creation_date'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='margin-top:10px;'>No reviews yet for this hotel.</p>";
            }

            echo "<button onclick='showAverageRating($hotel_id)' style='margin-top:15px;'>Show Average Rating</button>";
            echo "<p id='avg-$hotel_id' style='font-weight:bold; margin-top:10px;'></p>"; // Placeholder for average rating

            echo "</div>"; // End reviews div
            echo "</div>"; // End hotel div
        }

    } else {
        // This is the message you saw if the hotels table is empty
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
        var avgElement = document.getElementById('avg-' + hotelId);
        if (data.avg !== null) {
            avgElement.innerHTML = 'Average Rating: <span class="rating-text">' + data.avg + ' / 5</span>';
        } else {
            avgElement.innerText = 'No reviews yet.';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

</div> </body>
</html>