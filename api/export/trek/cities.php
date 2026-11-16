<?php
include_once '../../connectdb.php';

$link = connectDB();

$sql = 'SELECT * FROM `washere_places` WHERE `category_id` < 30 ORDER BY `population` DESC';

$result = $link->query($sql);

$places = array();
$labels = array(
    array("name" => "Gelebt", "color" => "#38BDF8"),
    array("name" => "Besucht", "color" => "#2FA37A"),
    array("name" => "Übernachtet", "color" => "#F59E0B"),
    array("name" => "Vorbeigeschaut", "color" => "#EF4444"),
    array("name" => "Durchgefahren", "color" => "#EC4899"),
);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        if ($row["status_id"] >= 5 && $row["status_id"] < 10) {
            continue;
        }

        $name = "";

        if ($row["name_native"] != "") {
            $name .= $row["name_native"] . " • ";
        }

        $name .= $row["name"];

        $description = "";

        if ($row["aka"] != "") {
            $description .= "## " . $row["aka"] . "\n";
        }

        if ($row["date"] != "") {
            $description .= $row["date"] . "\n";
        }

        if ($row["population"] > 0) {
            $description .= "Einwohner: " . number_format(intval($row["population"]), 0, ',', '.');
        }

        if ($row["population_metro"] > 0) {
            $description .= " (" . number_format(intval($row["population_metro"]), 0, ',', '.') . ")";
        }

        $place_labels = array();

        if ($row["status_id"] == 0 || $row["status_id"] >= 10) {
            $place_labels = array("Gelebt", "Besucht", "Übernachtet");
        } else if ($row["status_id"] == 1) {
            $place_labels = array("Besucht");
        } else if ($row["status_id"] == 2) {
            $place_labels = array("Übernachtet");
        } else if ($row["status_id"] == 3) {
            $place_labels = array("Vorbeigeschaut");
        } else if ($row["status_id"] == 4) {
            $place_labels = array("Durchgefahren");
        }

        $category = "Ortschaft";

        if (intval($row["population"]) >= 5000000) {
            $category = "Megacity";
        } else if (intval($row["population"]) >= 1000000) {
            $category = "Millionenstadt";
        } else if (intval($row["population"]) >= 100000) {
            $category = "Großstadt";
        } else if (intval($row["population"]) >= 50000) {
            $category = "Mittelstadt";
        } else if (intval($row["population"]) >= 5000) {
            $category = "Kleinstadt";
        }



        $place = array(
            "name" => $name,
            "description" => $description,
            "lat" => floatval($row["lat"]),
            "lng" => floatval($row["lon"]),
            "address" => $row["country"],
            "notes" => null,
            "price" => null,
            "currency" => null,
            "website" => null,
            "phone" => null,
            "image_url" => $row["image_url"],
            "google_place_id" => null,
            "google_ftid" => null,
            "osm_id" => null,
            "status" => "visited",
            "category" => $category,
            "labels" => $place_labels,
        );
        $places[] = $place;
    }
}

$collection = array(
    "format" => "trek.collection",
    "version" => 1,
    "name" => "washere",
    "description" => null,
    "color" => "#6366F1",
    "icon" => "Bookmark",
    "exported_at" => date("c"),
    "labels" => $labels,
    "places" => $places,
);

header('Content-Type: application/json');
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-Disposition: washere.trekcollection.json");
header("Content-Disposition: filename=washere.trekcollection.json");


echo json_encode($collection, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

closeDBConnection($link);

?>