<?php

include_once '../connectdb.php';

$link = connectDB();

$andWhere = "";

if (isset($_GET['w'])) {
    $with = $_GET['w'];
    $andWhere .= " AND (`company` LIKE '%" . $link->real_escape_string($with) . "%')";
}

if (isset($_GET['y'])) {
    $year = $_GET['y'];
    $andWhere .= " AND (`date` LIKE '%" . intval($year) . "%' OR `date` LIKE '%many times%')";
}

$stats = array();

$sql = 'SELECT COUNT(*) FROM washere_places WHERE 1' . $andWhere;

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["total_places"] = $row["COUNT(*)"];
} else {
    $stats["total_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%rural%"' . $andWhere;

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["rural_places"] = $row["COUNT(*)"];
} else {
    $stats["rural_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%minor%"' . $andWhere;

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["minor_places"] = $row["COUNT(*)"];
} else {
    $stats["minor_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%major%"' . $andWhere;

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["major_places"] = $row["COUNT(*)"];
} else {
    $stats["major_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%special%"' . $andWhere;

$result = $link->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stats["special_places"] = $row["COUNT(*)"];
} else {
    $stats["special_places"] = 0;
}

$sql = 'SELECT COUNT(*) FROM washere_places WHERE category LIKE "%capital%"' . $andWhere;

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