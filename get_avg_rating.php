<?php
require ('mysqli_connect.php'); // Connect to the db.
if (isset($_GET['hotel_id'])) {
    $hotel_id = (int)$_GET['hotel_id'];
    
    $query = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE hotel_id = $hotel_id";
    $result = mysqli_query($dbc, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $avg = $row['avg_rating'] !== null ? number_format($row['avg_rating'], 1) : null;
        echo json_encode(['avg' => $avg]);
    } else {
        echo json_encode(['avg' => null]);
    }
}

mysqli_close($dbc);
?>
