<?php
include_once 'connectdb.php';

$link = connectDB();

$sql = 'SELECT `id`,`name`,`continent`,`valid` from `washere_countries`';

$result = $link->query($sql);

$countries = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
       $countries[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($countries, JSON_PRETTY_PRINT);

closeDBConnection($link);

?>