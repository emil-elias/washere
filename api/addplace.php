<?php
include_once 'connectdb.php';
include_once 'uploadimage.php';

if (!isset($_POST['place_pwd']) || !isset($_POST['place_name']) || !isset($_POST['place_coords'])) {
    echo "Missing parameters";
    return;
}

$given_pwd = hash("sha256", $_POST['place_pwd']);

if ($given_pwd != $hashed_control_value) {
    echo "Wrong password";
    return;
}

$name = $_POST['place_name'];
$name = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $name);
$coords = $_POST['place_coords'];

$exploded_coords = explode(",", $coords);
$lat = floatval($exploded_coords[0]);
$lon = floatval($exploded_coords[1]);

if (isset($_POST['place_desc'])) {
    //$html_desc = nl2br($_POST['place_desc']);
    $html_desc = $_POST['place_desc'];
    $html_desc = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html_desc);
} else {
    $html_desc = "";
}

if (isset($_POST['place_category'])) {
    $category = $_POST['place_category'];
} else {
    $category = "rural";
}

if (isset($_POST['place_special']) && $_POST['place_special'] != "none") {
    $category .= "_" . $_POST['place_special'];
}

$image_url = "";

if (isset($_FILES) && isset($_FILES['place_image']) && is_uploaded_file($_FILES['place_image']['tmp_name'])){
    //$image = file_get_contents($_FILES['place_image']['tmp_name']);
    $image_url = uploadImg($_FILES['place_image'], $domain);
} else if (isset($_POST['place_image_url'])) {
    $image_url = $_POST['place_image_url'];
}

$link = connectDB();

//$image = $link->real_escape_string($image);

$sql = 'INSERT INTO `washere_places` (`name`, `lat`, `lon`, `html_desc`, `category`, `image_url`) VALUES (?, ?, ?, ?, ?, ?)';
$stmt = $link->prepare($sql);
$stmt->bind_param("sddsss", $name, $lat, $lon, $html_desc, $category, $image_url);
$stmt->send_long_data(5, $image);
$result = $stmt->execute();

if ($result === false) {
    die("Execute failed: " . $stmt->error);
} else {
    echo "Place '" . $name . "' added successfully at " . $lat . ", " . $lon;
}

closeDBConnection($link);

?>