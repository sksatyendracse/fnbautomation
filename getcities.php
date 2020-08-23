<?php
require_once(__DIR__ . '/inc/config.php');

    $stmt = $conn->prepare("SELECT * FROM cities LIMIT 50");
    $stmt->execute();
    $cities = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cities[$row['city_name']] = "";
    }
    echo json_encode($cities);
?>