<?php

try {

    $servername = "containers-us-west-40.railway.app";
    $username = "root";
    $password = "1rfGgzegoG0Ld4sSa6BP";
    $database = "railway";
    $port = 5678;

    // $servername = "localhost";
    // $username = "root";
    // $password = "root";
    // $database = "assignmentphp";
    
    $mysql = new mysqli($servername, $username, $password, $database, $port);
    // $mysql = new mysqli($servername, $username, $password, $database);
    if ($mysql === false) {
        die("ERROR: Could not connect. "
            . mysqli_connect_error());
    }


    $usersql = "CREATE TABLE IF NOT EXISTS `user` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `email` VARCHAR(45) NOT NULL,
        `passwordHash` VARCHAR(255) NOT NULL,
        `active` BOOLEAN DEFAULT 0
    )";


    $notesql = "CREATE TABLE IF NOT EXISTS `note` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `note` VARCHAR(250) NOT NULL,
        `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `user_id` INT NOT NULL,
        FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
        ON DELETE CASCADE
        )";

    $imagesql = "CREATE TABLE IF NOT EXISTS `image` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `file_name` VARCHAR(500) NOT NULL,
        `note_id` INT NOT NULL,
        FOREIGN KEY (`note_id`) REFERENCES `note`(`id`)
        ON DELETE CASCADE
    )";

    $mysql->query($usersql); // crate a user table if not exist
    $mysql->query($notesql); // crate a notes table if not exist
    $mysql->query($imagesql);
} catch (mysqli_sql_exception $e) {
    echo "could not connect to database";
    echo $e->getMessage();
    error_log($e->getMessage());
}
