<?php
include_once 'connectdb.php';

$search = "%" . $_GET['search'] . "%";

$link = connectDB();

$sql = 'SELECT `id`,`name`, `aka`, `lat`, `lon`, `html_desc`, `category_id`, `country`, `population`, `population_metro`, `date`, `company`, `song`, `status_id`, `image_url` from `washere_places` WHERE `name` LIKE ? or `aka` LIKE ?';

$stmt = $link->prepare($sql);
$stmt->bind_param("ss", $search, $search);

$stmt->execute();
$result = $stmt->get_result();

$places = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        //$row["html_desc"] = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $row["html_desc"]);
        $row["html_desc"] = htmlspecialchars($row["html_desc"]);
        $places[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($places, JSON_PRETTY_PRINT);

closeDBConnection($link);

?>