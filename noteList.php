<?php

echo "<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>";

if ($result->num_rows > 0) {
    $notes = array();
    $sortedNotes = usort($notes, function($a, $b) {
        return $a['createdAt'] - $b['createdAt'];
    });
    
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
    // sort notes by createAt
    
    foreach ($notes as $id => $note) {
       
        echo "<li class='list-group-item'>";

        echo "<div class='d-flex justify-content-between'>";
        echo "<h3>" . $note['note'] . "</h3>";
      
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='note_id' value='" . $id . "'>";
        echo "<div class='d-flex gap-3'>";
        echo "<button type='submit' name='edit' class='btn btn-outline-secondary' style='margin-right:5px;'><i class='far fa-edit'></i></button>";
        echo "<button type='submit' name='delete' class='btn btn-outline-secondary'><i class='far fa-trash-alt'></i></button>";
        echo "</div>";
        echo "</form>";
        echo "</div>";
        if (!empty($note['images'])) {
            echo "<div class='row mt-3'>";
            foreach ($note['images'] as $image) {
                echo "<div class='col-sm-4'>";
                echo "<div style='position: relative; display: inline-block; margin-right: 10px; margin-bottom: 10px;'>";
                echo "<img class='img-fluid rounded' src='uploads/images/" . $image . "' alt=''>";
                echo "</div>";
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


?>