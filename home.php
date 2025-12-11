<?php

    $page_title = 'Hotel Reviewer';
    include ('header.html');
    // Note: header.html should close the <body> and <html> tags

    echo '<h2>Available Hotels</h2>';
    require ('mysqli_connect.php');

    $q = "SELECT hotel_id, hotel_name,address , city, country FROM hotels ORDER BY country ASC";
    $result = mysqli_query($dbc, $q);

    if (!$result) {
        echo "Database Error: " . mysqli_error($dbc);
    }
    
    // Display hotels
    if (mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {

            $hid = $row['hotel_id'];     
            // Applied hotel-card class and data-label span
            echo "<div class='hotel-card'>";
            echo "<span class='data-label'>Hotel name:</span> " . htmlspecialchars($row['hotel_name']) . "<br>";
            echo "<span class='data-label'>Address:</span> " . htmlspecialchars($row['address']) . "<br>";
            echo "<span class='data-label'>City:</span> " . htmlspecialchars($row['city']) . "<br>";
            echo "<span class='data-label'>Country:</span> " . htmlspecialchars($row['country']) . "<br>";
            echo "</div>";

        }

    }else {
        echo "<p>No hotels found.</p>";
    }

?>
</div> </body>
</html>