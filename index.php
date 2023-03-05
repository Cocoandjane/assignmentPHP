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

include 'dbConfig.php';

// check if the user is logged in
// if (isset($_SESSION['email'])) {
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
session_start();

if (isset($_SESSION['email'])) {
    echo "user logged in";
} else {
    echo "user not logged in";
    // header("Location: login.php");
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
<style>
    <?php include 'index.css'; ?>
</style>

<body>
    <div class="container">
        <div class="row">

            <form action='index.php' method="post">>
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea type='text' name='note' placeholder='Add a note' class="form-control" id="note" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Add image</label>
                    <input class="form-control" type="file" id="formFileMultiple" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        </div>
    </div>
</body>

</html>