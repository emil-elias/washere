<?php
include_once 'connectdb.php';

$link = connectDB();

$sql = 'SELECT `id`, `name`, `lat`, `lon`, `html_desc`, `category`, `image_url` from `washere_places`';

$result = $link->query($sql);

$places = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        /*$image = null;
        if ($row["image"]) {
            $image = base64_encode($row["image"]);
        }*/

        $place = array("id" => $row["id"], "name" => $row["name"], "lat" => $row["lat"], "lon" => $row["lon"], "html_desc" => nl2br($row["html_desc"]), "category" => $row["category"], "image_url" => $row["image_url"]);
        $places[] = $place;
    }
}

header('Content-Type: application/json');
echo json_encode($places, JSON_PRETTY_PRINT);

closeDBConnection($link);

?>