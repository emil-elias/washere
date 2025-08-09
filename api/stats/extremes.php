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

$sql = "SELECT `name`, `aka`, `lat`, `lon`, `country`, `date` FROM `washere_places` ORDER BY `lat` DESC LIMIT 1;";

$result = $link->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row) {
        $stats["northernmost"] = $row;
    }
}

$sql = "SELECT `name`, `aka`, `lat`, `lon`, `country`, `date` FROM `washere_places` ORDER BY `lat` ASC LIMIT 1;";

$result = $link->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row) {
        $stats["southernmost"] = $row;
    }
}

$sql = "SELECT `name`, `aka`, `lat`, `lon`, `country`, `date` FROM `washere_places` ORDER BY `lon` DESC LIMIT 1;";

$result = $link->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row) {
        $stats["easternmost"] = $row;
    }
}

$sql = "SELECT `name`, `aka`, `lat`, `lon`, `country`, `date` FROM `washere_places` ORDER BY `lon` ASC LIMIT 1;";

$result = $link->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row) {
        $stats["westernmost"] = $row;
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