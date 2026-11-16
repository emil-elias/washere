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

if (isset($_POST['place_name_native'])) {
    $name_native = $_POST['place_name_native'];
    $name_native = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $name_native);
} else {
    $name_native = "";
}

if (isset($_POST['place_suffix'])) {
    $suffix = $_POST['place_suffix'];
    $suffix = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $suffix);
} else {
    $suffix = "";
}

if (isset($_POST['place_aka'])) {
    $aka = $_POST['place_aka'];
    $aka = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $aka);
} else {
    $aka = "";
}

$coords = $_POST['place_coords'];
$exploded_coords = explode(",", $coords);
$lat = floatval($exploded_coords[0]);
$lon = floatval($exploded_coords[1]);

$country = $_POST['place_country'];
$country = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $country);

if (isset($_POST['place_desc'])) {
    //$html_desc = nl2br($_POST['place_desc']);
    $html_desc = $_POST['place_desc'];
    $html_desc = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html_desc);
} else {
    $html_desc = "";
}

if (isset($_POST['place_population'])) {
    $population = intval($_POST['place_population']);
} else {
    $population = 0;
}

if (isset($_POST['place_metro_population'])) {
    $population_metro = intval($_POST['place_metro_population']);
} else {
    $population_metro = 0;
}

if (isset($_POST['place_date'])) {
    $date = $_POST['place_date'];
    $date = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $date);
} else {
    $date = "";
}

if (isset($_POST['place_company'])) {
    $company = $_POST['place_company'];
    $company = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $company);
} else {
    $company = "";
}

if (isset($_POST['place_song'])) {
    $song = $_POST['place_song'];
    $song = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $song);
} else {
    $song = "";
}

if (isset($_POST['place_status'])) {
    $status = $_POST['place_status'];
} else {
    $status = 1;
}

if (isset($_POST['place_category'])) {
    $category = $_POST['place_category'];
} else {
    $category = 32;
}

if (isset($_POST['place_special']) && $_POST['place_special'] != "none") {
    $category -= $_POST['place_special'];
}

$image_url = "";

if (isset($_FILES) && isset($_FILES['place_image']) && is_uploaded_file($_FILES['place_image']['tmp_name'])) {
    //$image = file_get_contents($_FILES['place_image']['tmp_name']);
    $image_url = uploadImg($_FILES['place_image'], $domain);
} else if (isset($_POST['place_image_url'])) {
    $image_url = $_POST['place_image_url'];
}

$link = connectDB();

//$image = $link->real_escape_string($image);

$sql = 'INSERT INTO `washere_places` (`name`, `name_native`, `suffix`, `aka`, `lat`, `lon`, `html_desc`, `category_id`, `country`, `population`, `population_metro`, `date`, `company`, `song`, `status_id`, `image_url`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
$stmt = $link->prepare($sql);
$stmt->bind_param("ssssddsisiisssis", $name, $name_native, $suffix, $aka, $lat, $lon, $html_desc, $category, $country, $population, $population_metro, $date, $company, $song, $status, $image_url);
$stmt->send_long_data(5, $image);
$result = $stmt->execute();

if ($result === false) {
    die("Execute failed: " . $stmt->error);
} else {
    echo "Place '" . $name . "' added successfully at " . $lat . ", " . $lon;
}

closeDBConnection($link);
