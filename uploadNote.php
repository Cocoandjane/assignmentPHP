<?php
$note = $_REQUEST['note'];
echo $note;

// if the user submitted a note, add it to the database
if (isset($_POST['note'])) {
    $note = $_POST['note'];
    echo $note;
    // $user_id = $_SESSION['user_id'];
    // $user_id = 1;
    // $sql = "INSERT INTO note (note, user_id) VALUES ('$note', $user_id)";
    // $mysql->query($sql);
    // $mysql->close();
    // header("Location: index.php");
}

?>