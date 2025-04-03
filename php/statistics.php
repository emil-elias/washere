<?php

include_once 'connectdb.php';

$link = connectDB();

$stats = array();

$sql = 'SELECT COUNT(*) FROM washere_places';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["total_places"] = $row["COUNT(*)"];
} else {
    $stats["total_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%rural%"';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["rural_places"] = $row["COUNT(*)"];
} else {
    $stats["rural_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%minor%"';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["minor_places"] = $row["COUNT(*)"];
} else {
    $stats["minor_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%major%"';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["major_places"] = $row["COUNT(*)"];
} else {
    $stats["major_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%special%"';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["special_places"] = $row["COUNT(*)"];
} else {
    $stats["special_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%capital%"';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["capitals"] = $row["COUNT(*)"];
} else {
    $stats["capitals"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_countries WHERE valid = 1';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["total_countries"] = $row["COUNT(*)"];
} else {
    $stats["total_countries"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_countries WHERE continent = "Europe" AND valid = 1';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["european_countries"] = $row["COUNT(*)"];
} else {
    $stats["european_countries"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_countries WHERE continent = "Asia" AND valid = 1';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["asian_countries"] = $row["COUNT(*)"];
} else {
    $stats["asian_countries"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_countries WHERE valid = 0';

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["invalid_countries"] = $row["COUNT(*)"];
} else {
    $stats["invalid_countries"] = 0;
}

header('Content-Type: application/json');
echo json_encode($stats, JSON_PRETTY_PRINT);

closeDBConnection($link);
?>