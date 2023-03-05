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
        <a href="./logout.php"><button type="button" class="btn btn-secondary">Logout</button></a>
        <div class="row">
            <?php if (isset($_POST['edit'])) {
            } ?>
            <form enctype="multipart/form-data" action="" method="post">
                <div class="form-group">
                    <label for="note">Note</label>
                    <textarea type='text' name='note' placeholder='Add a note' class="form-control" id="note" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <p>
                        <label for="userfile">Upload: </label>
                        <input type="file" name="userfile" id="userfile">
                    </p>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>

        </div>
    </div>
</body>

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
session_start();

// check if the user is logged in
if (isset($_SESSION['email'])) {
    // if the user is logged in, display the notes list
    $sessionEmail = $_SESSION['email'];
    echo $sessionEmail;

    // // get user id from email from database
    // Assuming that $sessionEmail is a safe value
    $sql = "SELECT id FROM user WHERE email = '$sessionEmail'";
    $result = $mysql->query($sql);
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // Use the $user_id value elsewhere in your code
    echo "The user ID is: " . $user_id;
} else {
    // if the user is not logged in, redirect to the login page
    header("Location: login.php");
}


if (isset($_POST['note'])) {

    if (isset($_FILES['userfile'])) {
        $note = $_POST['note'];
        echo $note;
        $sql = "INSERT INTO note (note, user_id) VALUES ('$note', $user_id)";
        $mysql->query($sql);
        $note_id = $mysql->insert_id;

        echo $note_id;

        $targetDir = "uploads/images/";
        $fileName = basename($_FILES["userfile"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $targetFilePath)) {
                // Insert image file name into database
                $insert = $mysql->query("INSERT into image (file_name, note_id) VALUES ('" . $fileName . "', '" . $note_id . "')");
                if ($insert) {
                    $statusMsg = "The file " . $fileName . " has been uploaded successfully.";
                } else {
                    $statusMsg = "File upload failed, please try again.";
                }
            } else {
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        } else {
            $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
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
    // redirect to new page
    header('Location: /assignmentPHP/index.php');
    exit;
}


$sql = "SELECT note.*, image.file_name 
        FROM note
        LEFT JOIN image ON note.id = image.note_id
        WHERE note.user_id = $user_id";

$result = $mysql->query($sql);

// display the notes list with images

if ($result->num_rows > 0) {

    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col'>";
    echo "<h1>Notes</h1>";
    echo "<ul>";
    while ($note = $result->fetch_assoc()) {
        echo "<li>";
        echo $note['note'];
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='note_id' value='" . $note['id'] . "'>";
        echo "<button type='submit' name='edit' class='btn btn-secondary'>Edit</button>";
        echo "<button type='submit' name='delete' class='btn btn-danger'>Delete</button>";
        echo "</form>";
        echo "</li>";
        echo "<img src='uploads/images/" . $note['file_name'] . "' alt=''>";
    }
    echo "</ul></div></div></div>";
} else {
    echo "<p>No notes found</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // process edit form
        $note_id = $_POST['note_id'];
        // ...
        echo $note_id;
        echo "edit";
    } elseif (isset($_POST['delete'])) {
        // process delete form
        $note_id = $_POST['note_id'];
        echo $note_id;
        echo "delete";
        $sql = "DELETE FROM note WHERE id = $note_id";
        $mysql->query($sql);
        $sql = "DELETE FROM image WHERE note_id = $note_id";
        $mysql->query($sql);
        header('Location: /assignmentPHP/index.php');
    }
}
?>