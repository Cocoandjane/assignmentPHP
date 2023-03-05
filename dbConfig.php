<?php

try {

    $servername = "containers-us-west-119.railway.app";
    $username = "root";
    $password = "yo5sZZTb4tea7SMAl4s2";
    $database = "railway";
    $port = 7628;

    $mysql = new mysqli($servername, $username, $password, $database, $port);
    if ($mysql === false) {
        die("ERROR: Could not connect. "
            . mysqli_connect_error());
    }


    $usersql = "CREATE TABLE IF NOT EXISTS `user` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `email` VARCHAR(45) NOT NULL,
        `passwordHash` VARCHAR(255) NOT NULL
    )";


    $notesql = "CREATE TABLE IF NOT EXISTS `note` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `note` VARCHAR(250) NOT NULL,
        `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `user_id` INT NOT NULL,
        FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
        )";

    $imagesql = "CREATE TABLE IF NOT EXISTS `image` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `file_name` VARCHAR(500) NOT NULL,
        `note_id` INT NOT NULL,
        FOREIGN KEY (`note_id`) REFERENCES `note`(`id`)
    )";

    $mysql->query($usersql); // crate a user table if not exist
    $mysql->query($notesql); // crate a notes table if not exist
    $mysql->query($imagesql);
} catch (mysqli_sql_exception $e) {
    echo "could not connect to database";
    echo $e->getMessage();
    error_log($e->getMessage());
}

?>