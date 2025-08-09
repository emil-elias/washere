<?php

include_once '../connectdb.php';

$link = connectDB();

$stats = array();

$sql = "SELECT 
    name,
    aka,
    country,
    population 
FROM 
    washere_places
ORDER BY
	population DESC;";

$result = $link->query($sql);


if ($result && $result->num_rows > 0) {
    $rank = 1;
    $stats["note"] = 
    "The population numbers refer to the time of visit and to official administrative boundaries - not metro or built up/urban areas. This might not be the best metric, since it's not always really comparable, but it is the easiest one. 
    Some cases, like Tokyo for example, are bit complicated. In case of Tokyo, I have only been inside the 23 special wards, which is also most widely considered the 'city of Tokyo', thats why only the population inside these wards is counted. 
    Some numbers, like the population of Bangkok for example, are estimates. If you can consider Bangkok 'bigger' than Tokyo, is, of course, debatable. But it is quite big.";
    while ($row = $result->fetch_assoc()) {
        //$row['rank'] = $rank++;
        if ($row['population'] == 0) {
            continue;
        }
       $stats[$rank] = $row;
       $rank++;
    }
}

header('Content-Type: application/json');
echo json_encode($stats, JSON_PRETTY_PRINT);

closeDBConnection($link);
?>