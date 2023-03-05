<?php
// if (isset($_POST['note'])) {

//     if (isset($_FILES['userfiles'])) {
//         $note = $_POST['note'];
//         echo $note;
//         $sql = "INSERT INTO note (note, user_id) VALUES ('$note', $user_id)";
//         $mysql->query($sql);
//         $note_id = $mysql->insert_id;

//         echo $note_id;

//         $targetDir = "uploads/images/";
//         $fileName = basename($_FILES["userfiles"]["name"]);
//         $targetFilePath = $targetDir . $fileName;
//         $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

//         // Allow certain file formats
//         $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
//         if (in_array($fileType, $allowTypes)) {
//             // Upload file to server
//             if (move_uploaded_file($_FILES["userfiles"]["tmp_name"], $targetFilePath)) {
//                 // Insert image file name into database
//                 $insert = $mysql->query("INSERT into image (file_name, note_id) VALUES ('" . $fileName . "', '" . $note_id . "')");
//                 if ($insert) {
//                     $statusMsg = "The file " . $fileName . " has been uploaded successfully.";
//                 } else {
//                     $statusMsg = "File upload failed, please try again.";
//                 }
//             } else {
//                 $statusMsg = "Sorry, there was an error uploading your file.";
//             }

//         } else {
//             $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
//         }
//     } else {
//         $note = $_POST['note'];
//         echo $note;
//         $sql = "INSERT INTO note (note, user_id) VALUES ('$note', $user_id)";
//         $mysql->query($sql);
//         $note_id = $mysql->insert_id;
//         echo $note_id;
//         $statusMsg = 'Please select a file to upload.';
//     }
//  // redirect to new page
//  header('Location: /assignmentPHP/index.php');
//  exit;

// }



// <!-- <body>
//     <div class="container">
//         <a href="./logout.php"><button type="button" class="btn btn-secondary">Logout</button></a>
//         <div class="row">
//             <form enctype="multipart/form-data" action="" method="post">
//                 <div class="form-group">
//                     <label for="note">Note</label>
//                     <textarea type='text' name='note' placeholder='Add a note' class="form-control" id="note" rows="3"></textarea>
//                 </div>
//                 <div class="mb-3">
//                     <p>
//                         <label for="userfiles">Upload: </label>
//                         <input type="file" name="userfiles[]" id="userfiles" multiple>
//                     </p>
//                     <button type="submit" class="btn btn-primary">Submit</button>
//                 </div>
//             </form>
//         </div>
//     </div>

// </body> -->
?>