<?php

include_once 'connectdb.php';
include_once 'uploadimage.php';

if (!isset($_POST['place_id']) || !isset($_POST['place_pwd'])) {
    echo "Missing parameters";
    return;
}

$given_pwd = hash("sha256", $_POST['place_pwd']);

if ($given_pwd != $hashed_control_value) {
    echo "Wrong password";
    return;
}

$id = $_POST['place_id'];

$link = connectDB();

$sql = 'DELETE FROM `washere_places` WHERE `id` = ?';
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $id);
$result = $stmt->execute();
if ($result) {
    echo "Place with id " . $id . " removed successfully. \n";
    if (isset($_POST['old_place_image_url'])) {
        removeImg($_POST['old_place_image_url'], $domain);
    }
} else {
    echo "Error removing place with id " . $id;
}

closeDBConnection($link);
?>