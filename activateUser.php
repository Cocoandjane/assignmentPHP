<?php

// find the user in the db, and set the active field to true
// redirect to login page

$servername = "containers-us-west-40.railway.app";
$username = "root";
$password = "1rfGgzegoG0Ld4sSa6BP";
$database = "railway";
$port = 5678;

try {
    $mysql = new mysqli($servername, $username, $password, $database, $port);
    if ($mysql === false) {
        die("ERROR: Could not connect. "
            . mysqli_connect_error());
    } else {
        echo "Connected to database successfully";
    }

    $url = $_SERVER['REQUEST_URI'];
    $url_components = parse_url($url);
    parse_str($url_components['query'], $params);

    $email = $params['email'];

    $sql = "UPDATE `user` SET `active` = 1 WHERE `email` = '$email'";
    $mysql->query($sql);
    header("location: login.php");
} catch (mysqli_sql_exception $e) {
    throw $e;
}

?>