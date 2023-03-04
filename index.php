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
        `email` VARCHAR(45) NOT NULL,
        `hash` VARCHAR(45) NOT NULL
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
    echo $e->getMessage();
    error_log($e->getMessage());
}

// // check if the user is logged in
// if (isset($_SESSION['user_id'])) {
//     // if the user is logged in, display the notes list
//     $user_id = $_SESSION['user_id'];
//     $sql = "SELECT * FROM note WHERE user_id = $user_id";
//     $result = $mysql->query($sql);
//     $notes = $result->fetch_all(MYSQLI_ASSOC);
//     $mysql->close();
//     // display the notes list
//     echo "<h1>Notes</h1>";
//     echo "<ul>";
//     foreach ($notes as $note) {
//         echo "<li>" . $note['note'] . "</li>";
//     }
//     echo "</ul>";
// } else {
//     // if the user is not logged in, redirect to the login page
//     header("Location: login.php");
// }

// make a form to allow the user to add a note
echo "<form action='index.php' method='post'>";
echo "<input type='text' name='note' placeholder='Add a note'>";
echo "<input type='submit' value='Add Note'>";
echo "</form>";

// if the user submitted a note, add it to the database
if (isset($_POST['note'])) {
    $note = $_POST['note'];
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO note (note, user_id) VALUES ('$note', $user_id)";
    $mysql->query($sql);
    $mysql->close();
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="row">
        
        </div>
    </div>
</body>

</html>