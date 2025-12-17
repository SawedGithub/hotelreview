<?php

    $page_title = 'Hotel Reviewer';
    include ('header.html');
    // Note: header.html should close the <body> and <html> tags

    echo '<h2>Available Hotels</h2>';
    require ('mysqli_connect.php');

    

    $q = "SELECT hotel_id, hotel_name, address, city, country, image FROM hotels ORDER BY country ASC";
    $result = mysqli_query($dbc, $q);
    
    // Display hotels
    if (mysqli_num_rows($result) > 0) {
        
            while ($row = mysqli_fetch_assoc($result)) {

                echo "<div class='hotel-card'>";

                echo "<div class='hotel-image-wrapper'>";
                $imgPath = "hotelimg/" . htmlspecialchars($row['image']);
                echo "<img src='$imgPath' class='hotel-image' alt='Hotel image'>";
                echo "</div>";

                echo "<div class='hotel-card-content'>";
                echo "<span class='data-label'>Hotel name:</span> " . htmlspecialchars($row['hotel_name']) . "<br>";
                echo "<span class='data-label'>Address:</span> " . htmlspecialchars($row['address']) . "<br>";
                echo "<span class='data-label'>City:</span> " . htmlspecialchars($row['city']) . "<br>";
                echo "<span class='data-label'>Country:</span> " . htmlspecialchars($row['country']) . "<br>";
                echo "</div>";

                echo "</div>";


            }


    }else {
        echo "<p>No hotels found.</p>";
    }

?>
</div> </body>
</html>