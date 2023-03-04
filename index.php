<?php
/*
5. The application must allow the user to store notes in a database and retrieve them
6. The application must allow the user to store images in a database (not in the filesystem) and retrieve them
7. Users must be able to update notes by editing a textarea within a form
8. Users must be able to upload a maximum of four images, and delete images
9. The system must provide the following access controls:
a. Anyone who is not logged in, and who attempts to view any page besides the login / register page(s)
will be redirected to the register page.
b. A logout function is provided - phoenix
c. Users who are inactive for more than 10 minutes are logged out - phoenix
*/

// notes READ and DELETE, display a notes list, and allow the user to delete notes that they created
// if the user is not logged in, redirect to the login page - phoenix

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

    // Create a user table if not exist
    $usersql = "CREATE TABLE IF NOT EXISTS `user` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `email` VARCHAR(45) NOT NULL 
    )";

    // create a notes table if not exist
    $notesql = "CREATE TABLE IF NOT EXISTS `note` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `note` VARCHAR(250) NOT NULL,
        `createdAd` DATE NOT NULL,
        `user_id` INT NOT NULL,
        FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
        )";

    $imagesql = "CREATE TABLE IF NOT EXISTS `image` (
        `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `blob` VARCHAR(500) NOT NULL,
        `note_id` INT NOT NULL,
        FOREIGN KEY (`note_id`) REFERENCES `note`(`id`)
    )";

    $mysql->query($usersql); // crate a user table if not exist
    $mysql->query($notesql); // crate a notes table if not exist
    $mysql->query($imagesql);
} catch (mysqli_sql_exception $e) {
    echo "could not connect to database";
    error_log($e->getMessage());
}
