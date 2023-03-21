<?php



if (isset($_POST['edit'])) {
    $_SESSION['last_activity'] = time(); // update activity time stamp
    // edit form
    $note_id = $_POST['note_id'];
    $sql = "SELECT * FROM note WHERE id = $note_id";
    $result = $mysql->query($sql);

    if ($result->num_rows > 0) {
        $note = $result->fetch_assoc();
        echo "<div class='container'>";
        echo "<form enctype='multipart/form-data' class='position-relative' action='' method='post'>";
        echo "<div class='form-group'>";
        echo "<label for='note'></label>";
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
                echo "<div style='position: relative; display: inline-block; vertical-align: top; margin-right: 10px; margin-bottom: 10px;'>";
                echo "<img style='border-radius: 5px; ' src='uploads/images/" . $image['file_name'] . "'>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='image_id' value='" . $image['id'] . "'>";
                echo "<button type='submit' name='deleteImage'  style='position: absolute; top: 0; right: 0; margin: 10px;'><i class='far fa-trash-alt'></i></button>";
                echo "</form>";
                echo "</div>";
            }
        }
        echo "<button type='submit' name='update' class='btn btn-primary' style='position: absolute; bottom: 0; right: 0;'>Update</button>";
        echo "</div></form>";
        echo "</div>";

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
    if (!empty($_FILES['userfiles']['tmp_name'])) {
        $uploadedFiles = array();
        $extension = array("jpeg", "jpg", "png", "gif");
    
        foreach ($_FILES['userfiles']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['userfiles']['name'][$key];
            $file_tmp = $_FILES['userfiles']['tmp_name'][$key];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_error = $_FILES['userfiles']['error'][$key];
    
            if ($file_error == UPLOAD_ERR_OK && in_array($file_ext, $extension)) {
                $uploadedFiles[] = $file_name;
                move_uploaded_file($file_tmp, "uploads/images/" . $file_name);
                // save images to db
                $sql = "INSERT INTO image (file_name, note_id) VALUES ('$file_name', $note_id)";
                $mysql->query($sql);
            }
        }
    
        if (!empty($uploadedFiles)) {
            echo "<div class='alert'>updated successfully</div>";
            echo '<script>setTimeout(function() { $(".alert").remove(); }, 3000);</script>';
            header('Location: /assignmentPHP/index.php');   
        } else {
            header('Location: /assignmentPHP/index.php');    
        }
    }
    
 
} else {
    // post form
    echo "<div class='container'>";
    echo "<form enctype='multipart/form-data' class='position-relative' action='' method='post'>";
    echo "<div class='form-group'>";
    echo "<label for='note'></label>";
    echo "<textarea type='text' name='note' placeholder='Add a note' class='form-control' id='note' rows='3'></textarea>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<p><label for='userfiles' class='primaryButton'>Upload image</label>";
    echo "<input type='file' name='userfiles[]' id='userfiles' multiple>";
    echo "</p>";
    echo "<button type='submit' name='submit' class='btn btn-primary' style='position: absolute; bottom: 0; right: 0;'>Submit</button>";
    echo "</div></form>";
    echo "</div>";

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