<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Login</title>
</head>
<style>
    <?php include 'index.css'; ?>
</style>
<?php
include 'dbConfig.php';

session_start();

// Check if last activity was set and if user is inactive for 10 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 6000) {
    // last request was more than 10 minutes ago
    session_unset();
    session_destroy();
    header("Location: login.php");
} else {
    // $_SESSION['last_activity'] = time(); // put this update last activity time stamp where there is activity
    echo "<div class='welcome'><div >Welcome: " . $_SESSION['email'] . "</div><a href='logout.php'><button class='btn btn-outline-secondary'>Logout</button></a></div>";
}




?>


</html>
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

include 'form.php';
// session_start();

// check if the user is logged in
if (isset($_SESSION['email'])) {

    $sessionEmail = $_SESSION['email'];

    $sql = "SELECT id FROM user WHERE email = '$sessionEmail'";
    $result = $mysql->query($sql);
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
} else {
    header("Location: login.php");
}





if (isset($_POST['note']) && isset($_POST['submit'])) {
    $_SESSION['last_activity'] = time(); // update activity time stamp
    // if note is empty, don't save it
    if (empty($_POST['note'])) {

        $statusMsg = 'Please enter a note.';
        echo $statusMsg;

    } else
        if (isset($_FILES['userfiles'])) {
            $note = $_POST['note'];
            echo $note;
            $sql = "INSERT INTO note (note, user_id) VALUES ('$note', $user_id)";
            $mysql->query($sql);
            $note_id = $mysql->insert_id;

            echo $note_id;

            if (isset($_FILES['userfiles'])) {
                $total_files = count($_FILES['userfiles']['name']);
                if ($total_files < 4) {
                    $errors = array();
                    $uploadedFiles = array();
                    $extension = array("jpeg", "jpg", "png", "gif");

                    foreach ($_FILES['userfiles']['tmp_name'] as $key => $tmp_name) {
                        $file_name = $_FILES['userfiles']['name'][$key];
                        $file_tmp = $_FILES['userfiles']['tmp_name'][$key];
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                        if (in_array($file_ext, $extension) === false) {
                            $errors[] = "extension not allowed, please choose a JPEG, PNG, or GIF file.";
                        }

                        if (empty($errors) == true) {
                            $uploadedFiles[] = $file_name;
                            // move_uploaded_file($file_tmp, "uploads/images/" . $file_name);
                            move_uploaded_file($file_tmp, "uploads/images/" . $file_name);
                            // save images to db
                            $sql = "INSERT INTO image (file_name, note_id) VALUES ('$file_name', $note_id)";
                            $mysql->query($sql);
                        }
                    }

                    if (empty($errors)) {
                        echo "Images uploaded successfully:";
                        echo "<ul>";
                        foreach ($uploadedFiles as $name) {
                            echo "<li>$name</li>";
                        }
                        echo "</ul>";
                    } else {
                        print_r($errors);
                    }
                } else {
                    $statusMsg = 'You can only upload a maximum of 4 images.';
                }
            } else {
                $note = $_POST['note'];
                echo $note;
                $sql = "INSERT INTO note (note, user_id) VALUES ('$note', $user_id)";
                $mysql->query($sql);
                $note_id = $mysql->insert_id;
                echo $note_id;
                $statusMsg = 'Please select a file to upload.';
            }
            header('Location: /assignmentPHP/index.php');
            exit;
        }
}

$sql = "SELECT note.* , image.file_name 
        FROM note
        LEFT JOIN image ON note.id = image.note_id
        WHERE note.user_id = $user_id
        ORDER BY note.createdAt DESC";

$result = $mysql->query($sql);

include 'noteList.php';

?>