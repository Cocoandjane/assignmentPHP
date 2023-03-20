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
<?php
include 'dbConfig.php';
session_start();
if (isset($_POST['edit'])) {
    $_SESSION['last_activity'] = time(); // update activity time stamp
    // edit form
    $note_id = $_POST['note_id'];
    $sql = "SELECT * FROM note WHERE id = $note_id";
    $result = $mysql->query($sql);

    if ($result->num_rows > 0) {
        $note = $result->fetch_assoc();
        echo "<form enctype='multipart/form-data' action='' method='post'>";
        echo "<div class='form-group'>";
        echo "<label for='note'>Note</label>";
        echo "<textarea type='text' name='note' class='form-control' id='note' rows='3'>" . $note['note'] . "</textarea>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "<p><label for='userfiles'>Upload:</label>";
        echo "<input type='file' name='userfiles[]' id='userfiles' multiple>";
        echo "</p>";
        echo "<input type='hidden' name='note_id' value='" . $note_id . "'>";
        $sql = "SELECT * FROM image WHERE note_id = $note_id";
        $result = $mysql->query($sql);
        if ($result->num_rows > 0) {
            while ($image = $result->fetch_assoc()) {
                echo "<img src='uploads/images/" . $image['file_name'] . "'>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='image_id' value='" . $image['id'] . "'>";
                echo "<button type='submit' name='deleteImage' class='btn btn-secondary'>Delete image</button>";
                echo "</form>";
            }
        }

        echo "<button type='submit' name='update' class='btn btn-primary'>Update</button>";
        echo "</div></form>";

        // display images

    } else {
        echo "<p>No notes found</p>";
    }
} else if (isset($_POST['update'])) {
    $_SESSION['last_activity'] = time(); // update activity time stamp

    $note_id = $_POST['note_id'];
    $note = $_POST['note'];
    $query = "UPDATE note SET note = '$note' WHERE id = $note_id";
    $result = $mysql->query($query);

    if ($result) {
        // Display a success message
        $statusMsg = "Note updated successfully.";
    } else {
        // Display an error message
        $statusMsg = "Error updating note.";
    }
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
            echo $statusMsg;
        }
    }
    header('Location: /assignmentPHP/index.php');
} else {
    // post form
    echo "<form enctype='multipart/form-data' action='' method='post'>";
    echo "<div class='form-group'>";
    echo "<label for='note'>Note</label>";
    echo "<textarea type='text' name='note' placeholder='Add a note' class='form-control' id='note' rows='3'></textarea>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<p><label for='userfiles'>Upload:</label>";
    echo "<input type='file' name='userfiles[]' id='userfiles' multiple>";
    echo "</p>";
    echo "<button type='submit' name='submit' class='btn btn-primary'>Submit</button>";
    echo "</div></form>";
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
// session_start();

// check if the user is logged in
if (isset($_SESSION['email'])) {
    // if the user is logged in, display the notes list
    $sessionEmail = $_SESSION['email'];

    // // get user id from email from database
    // Assuming that $sessionEmail is a safe value
    $sql = "SELECT id FROM user WHERE email = '$sessionEmail'";
    $result = $mysql->query($sql);
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    // Use the $user_id value elsewhere in your code
    // echo "The user ID is: " . $user_id;
} else {
    // if the user is not logged in, redirect to the login page
    header("Location: login.php");
}
// $user_id = 1;

// Check if last activity was set and if user is inactive for 10 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 600) {
    // last request was more than 10 minutes ago
    session_unset();
    session_destroy();
    header("Location: login.php");
} else {
    // $_SESSION['last_activity'] = time(); // put this update last activity time stamp where there is activity
    echo "<a href='logout.php'><button class='btn btn-secondary'>Logout</button></a>";
}



if (isset($_POST['note']) && isset($_POST['submit'])) {
    $_SESSION['last_activity'] = time(); // update activity time stamp

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
                echo $statusMsg;
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

$sql = "SELECT note.*, image.file_name 
        FROM note
        LEFT JOIN image ON note.id = image.note_id
        WHERE note.user_id = $user_id";

$result = $mysql->query($sql);

// display the notes list with images

if ($result->num_rows > 0) {
    $notes = array();
    while ($note = $result->fetch_assoc()) {
        $id = $note['id'];
        if (!isset($notes[$id])) {
            $notes[$id] = array(
                'note' => $note['note'],
                'images' => array()
            );
        }
        if ($note['file_name']) {
            $notes[$id]['images'][] = $note['file_name'];
        }
    }
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col'>";
    echo "<h1>Notes</h1>";
    echo "<ul class='list-group'>";
    foreach ($notes as $id => $note) {
        echo "<li class='list-group-item'>";
        echo "<h3>" . $note['note'] . "</h3>";
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='note_id' value='" . $id . "'>";
        echo "<button type='submit' name='edit' class='btn btn-secondary mr-2'>Edit</button>";
        echo "<button type='submit' name='delete' class='btn btn-danger'>Delete</button>";
        echo "</form>";
        if (!empty($note['images'])) {
            echo "<div class='row mt-3'>";
            foreach ($note['images'] as $image) {
                echo "<div class='col-sm-4'>";
                echo "<img class='img-fluid rounded' src='uploads/images/" . $image . "' alt=''>";
                echo "</div>";
            }
            echo "</div>";
        }
        echo "</li>";
    }
    echo "</ul></div></div></div>";
} else {
    echo "<p>No notes found</p>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['last_activity'] = time(); // update activity time stamp

    if (isset($_POST['delete'])) {
        // process delete form
        $note_id = $_POST['note_id'];
        $sql = "DELETE FROM note WHERE id = $note_id";
        $mysql->query($sql);
        $sql = "DELETE FROM image WHERE note_id = $note_id";
        $mysql->query($sql);

        header('Location: /assignmentPHP/index.php');
    } else if (isset($_POST['deleteImage'])) {
        $image_id = $_POST['image_id'];
        $query = "DELETE FROM image WHERE id = $image_id";
        $result = $mysql->query($query);
        header('Location: /assignmentPHP/index.php');
    }
}
?>