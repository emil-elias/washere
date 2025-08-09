<?php

$hashed_control_value = "0f65992ee7e0d7f3ac5ac713c775c9da70724738300f68feab63fbcc05925a13";

function connectDB()
{

    $host_name = 'db5014679773.hosting-data.io';
    $database = 'dbs12195827';
    $user_name = 'dbu5621106';
    $password = 'vS0f9nGCJhukbwqMmjcjybZfmrgc8p6pbkOgLRFBJTHBjwBFAv';

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