<?php

/*include_once 'connectdb.php';

if (!isset($_POST['place_pwd'])) {
    echo "Missing parameters";
    return;
}

$given_pwd = hash("sha256", $_POST['place_pwd']);

if ($given_pwd != $hashed_control_value) {
    echo "Wrong password";
    return;
}

if (isset($_FILES) && isset($_FILES['place_image']) && is_uploaded_file($_FILES['place_image']['tmp_name'])){
    //$image = file_get_contents($_FILES['place_image']['tmp_name']);
    uploadImg($_FILES['place_image'], $domain);
} else {
    echo "No image uploaded";
}*/

function uploadImg( $file, $domain ) {
    $target_dir = "../images/places/";
    //$target_file = $target_dir . basename($file["name"]);
    $target_file = $target_dir . uniqid(`img_`, true) . "." . strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".\n";
            $uploadOk = 1;
        } else {
            echo "File is not an image.\n";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "File overwritten.";
    }
    // Check file size
    if ($file["size"] > 500000) {
        echo "Sorry, your file is too large.\n";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.\n";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.\n";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            echo "The file ". basename( $file["name"]). " has been uploaded to " . str_replace(".." , $domain, $target_file) . "\n";
        } else {
            echo "Sorry, there was an error uploading your file.\n";
            $uploadOk = 0;
        }
    }

    if ($uploadOk == 0) {
        return "";
    }

    return str_replace(".." , $domain, $target_file);
}

function removeImg( $path, $domain ) {

    if (!str_contains($path, $domain)) {
        echo "Image file is not local.\n";
        return;
    }

    $path = str_replace($domain, "..", $path);
    echo "Removing image file: " . $path . "\n";

    if (file_exists($path)) {
        echo "Image file removed.\n";
        unlink($path);
    }
}

?>