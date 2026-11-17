<?php
include_once '../../connectdb.php';

$link = connectDB();

$sql = 'SELECT *,
            CASE
                WHEN population_urban > 0 AND population_metro > 0
                    THEN (population + population_urban + population_metro) / 3.0
                WHEN population_metro > 0
                    THEN (population + population_metro) / 2.0
                WHEN population_urban > 0
                    THEN (population + population_urban) / 2.0
                ELSE population
            END AS population_mean 
        FROM `washere_places` 
        WHERE `category_id` < 30 
        ORDER BY `population_mean` DESC';

$result = $link->query($sql);

//$reference = json_decode(file_get_contents('reference.json'), true);

$places = array();
$labels = array(
    array("name" => "Gelebt", "color" => "#38BDF8"),
    array("name" => "Besucht", "color" => "#2FA37A"),
    array("name" => "Übernachtet", "color" => "#F59E0B"),
    array("name" => "Vorbeigeschaut", "color" => "#EF4444"),
    array("name" => "Durchgefahren", "color" => "#EC4899"),
    array("name" => "Solo", "color" => "#64748B"),
    array("name" => "mit Familie", "color" => "#64748B"),
    array("name" => "mit Freunden", "color" => "#64748B"),
    array("name" => "mit Karlotta", "color" => "#64748B"),
    array("name" => "mit Lotte", "color" => "#64748B"),
    array("name" => "Akademisch", "color" => "#64748B"),
    array("name" => "Beruflich", "color" => "#64748B"),
);

function translate_status($status_id)
{
    switch ($status_id) {
        case 0:
            return "Gelebt";
        case 1:
            return "Besucht";
        case 2:
            return "Übernachtet";
        case 3:
            return "Vorbeigeschaut";
        case 4:
            return "Durchgefahren";
        default:
            return "Besucht";
    }
}

function translate_country($country)
{

    $capital_string = "";

    $is_capital = str_contains($country, "(Capital)");

    if ($is_capital) {
        $country = trim(str_replace("(Capital)", "", $country));
        $capital_string = " (Hauptstadt)";
    }

    $country_translations = array(
        "Germany" => "Deutschland",
        "United Kingdom" => "Vereinigtes Königreich",
        "United Kingdom (England)" => "England, Vereinigtes Königreich",
        "United Kingdom (Scotland)" => "Schottland, Vereinigtes Königreich",
        "Italy" => "Italien",
        "France" => "Frankreich",
        "Spain" => "Spanien",
        "Poland" => "Polen",
        "Belgium" => "Belgien",
        "Netherlands" => "Niederlande",
        "Denmark" => "Dänemark",
        "Norway" => "Norwegen",
        "Sweden" => "Schweden",
        "Czech Republic" => "Tschechien",
        "Austria" => "Österreich",
        "Switzerland" => "Schweiz",
        "Slovakia" => "Slowakei",
        "Slovenia" => "Slowenien",
        "Hungary" => "Ungarn",
        "Vatican City" => "Vatikanstadt",
        "Turkey" => "Türkei",
        "Cambodia" => "Kambodscha",
        "Singapore Republic" => "Singapur",
    );

    $translated_country = isset($country_translations[$country]) ? $country_translations[$country] : $country;
    return $translated_country . $capital_string;
}

function translate_months($date)
{
    $month_translations = array(
        "Jan" => "Januar",
        "Feb" => "Februar",
        "Mar" => "März",
        "Apr" => "April",
        "May" => "Mai",
        "Jun" => "Juni",
        "Jul" => "Juli",
        "Aug" => "August",
        "Sep" => "September",
        "Oct" => "Oktober",
        "Nov" => "November",
        "Dec" => "Dezember",
        "many times" => "sehr oft",
        "multiple times" => "mehrmals",
    );

    foreach ($month_translations as $english_month => $german_month) {
        $date = str_replace($english_month, $german_month, $date);
    }

    return $date;
}

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

        if ($row["aka"] != "" || $row["name_other"] != "") {
            $description .= "> ## ";
            if ($row["name_other"] != "") {
                $description .= $row["name_other"];
            }
            if ($row["aka"] != "" && $row["name_other"] != "") {
                $description .= " • ";
            }
            if ($row["aka"] != "") {
                $description .= $row["aka"];
            }
            
            $description .= "\n\n";
        }

        if ($row["date"] != "") {
            $split_date = explode(",", $row["date"]);
            $status = translate_status($row["status_id"]);
            $description .= "> **" . $status . ":**\n";
            foreach ($split_date as $date) {
                $description .= "> - " . translate_months(trim($date)) . "\n";
            }
            $description .= "\n";
        }

        if ($row["population"] > 0) {
            $description .= "> **Einwohner:**\n";
            $description .= "> - Stadt: " . number_format(intval($row["population"]), 0, ',', '.') . "\n";
            if ($row["population_urban"] > 0) {
                $description .= "> - Agglomeration: " . number_format(intval($row["population_urban"]), 0, ',', '.') . "\n";
            }
            if ($row["population_metro"] > 0) {
                $description .= "> - Metropolregion: " . number_format(intval($row["population_metro"]), 0, ',', '.') . "\n";
            }
        }



        $place_labels = array();

        if ($row["status_id"] == 0 || $row["status_id"] >= 10) {
            $place_labels = array("Gelebt", "Besucht", "Übernachtet");
        } else if ($row["status_id"] == 1) {
            $place_labels = array("Besucht");
            if ($row["overnight"] == 1) {
                $place_labels[] = "Übernachtet";
            }
        } else if ($row["status_id"] == 2) {
            $place_labels = array("Übernachtet");
        } else if ($row["status_id"] == 3) {
            $place_labels = array("Vorbeigeschaut");
            if ($row["overnight"] == 1) {
                $place_labels[] = "Übernachtet";
            }
        } else if ($row["status_id"] == 4) {
            $place_labels = array("Durchgefahren");
        }

        if ($row["company"] == "") {
            $place_labels[] = "Solo";
        }


        if (str_contains($row["company"], "Family") || str_contains($row["company"], "Mama") || str_contains($row["company"], "Papa") || str_contains($row["company"], "Elisa") || str_contains($row["company"], "Mattis") || str_contains($row["company"], "Malina") || str_contains($row["company"], "Oma") || str_contains($row["company"], "Opa")) {
            $place_labels[] = "mit Familie";
        }

        if (str_contains($row["company"], "Karlotta")) {
            $place_labels[] = "mit Karlotta";
        }

        if (str_contains($row["company"], "Lotte")) {
            $place_labels[] = "mit Lotte";
        }

        $category = "Ortschaft";

        if (intval($row["population_mean"]) >= 5000000) {
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

        $address = $row["name"];

        if ($row["name_german"] != "") {
            $address = $row["name_german"];
        }

        if ($row["region"] != "") {
            $address .= ", " . $row["region"];
        }

        if ($row["country"] != "") {
            $address .= ", " . translate_country($row["country"]);
        }

        $place = array(
            "name" => $name,
            "description" => $description,
            "lat" => floatval($row["lat"]),
            "lng" => floatval($row["lon"]),
            "address" => $address,
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
//header("Content-Type: application/force-download");
//header("Content-Type: application/download");
//header("Content-Disposition: washere.trekcollection.json");
//header("Content-Disposition: filename=washere.trekcollection.json");


echo json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

closeDBConnection($link);
