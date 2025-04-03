<?php

include_once '../connectdb.php';

$link = connectDB();

$stats = array();

$sql = "SELECT 
    c.name AS country, 
    COUNT(p.html_desc) AS places
FROM 
    washere_countries AS c
JOIN 
    washere_places AS p
ON 
    p.html_desc LIKE CONCAT('%', c.name, '%')
GROUP BY 
    c.name
ORDER BY
	places DESC;";

$result = $link->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
       $stats[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($stats, JSON_PRETTY_PRINT);

closeDBConnection($link);
?>