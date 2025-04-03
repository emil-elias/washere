<?php

$hashed_control_value = "";

function connectDB()
{

    $host_name = '';
    $database = '';
    $user_name = '';
    $password = '';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Failed to connect to database: ' . $link->connect_error . '</p>');
    }

    return $link;
}

function closeDBConnection($con)
{
    $con->close();
}

?>