<?php

/**
 * ich hab keine ahnung warum das hier alles nix zurück gibt
 * ich glaube langsam, ich werd einfach nur getrollt
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../connectdb.php';

$link = connectDB();

$stats = array();

$year = date("Y");

$stopYear = 2002;

for ($year; $year >= $stopYear; $year--) {
    $sql = "SELECT COUNT(*) as count FROM `washere_places` WHERE `date` LIKE '%" . $year . "%';";
    $result = $link->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row) {
            $stats[$year] = $row["count"];
        }
    }
}

header('Content-Type: application/json');
//echo json_encode($stats, JSON_PRETTY_PRINT);

$json = json_encode($stats, JSON_PRETTY_PRINT);

if ($json === false) {
    die("JSON encode error: " . json_last_error_msg());
}

echo $json;

closeDBConnection($link);
?>