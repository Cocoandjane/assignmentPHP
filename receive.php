<?php
/*
1. The system must have membership-signup functionality including:
a. No duplicate usernames are permitted
b. Username must be an email address
c. Passwords are hashed when stored (i.e., the database does not contain the actual password)
2. There must be a “reset password” function whereby users who forget their passwords can get an email
with a new password in it. Your application must generate a new password and update the user’s
database record with it.
3. After registering, an email is sent to the user with a hyperlink in it. When the user clicks the link, the
account becomes “active” and users can log in. Users cannot login to the system until this link has been
clicked.
4. If a user fails three times in a row to give the correct password for a given username, the account
becomes “locked” and an email is sent to the user
*/

// receive the login data and validate
// if valid, redirect to the notes list page?
// if (!empty($_POST)) {
//     $email = $_POST['email'];
//     $password = $_POST['password'];


//     // validate the email
//     if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         echo ("$email is a valid email address");
//     } else {
//         echo ("$email is not a valid email address");
//     }

//     //hash the password
//     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
//     echo $email;
//     echo "<br>";
//     echo $password;
//     echo "<br>";
//     echo $hashed_password;
// }

session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $email = $_POST['username'];
    $password = $_POST['password'];

    //connection, get jane variables
    $conn = new mysqli('localhost', 'root', '', 'dbase');

    //set login attempt if not set
    if (!isset($_SESSION['attempt'])) {
        $_SESSION['attempt'] = 0;
    }

    //check if there are 3 attempts already
    if ($_SESSION['attempt'] == 3) {
        $_SESSION['error'] = 'Attempt limit reach';
    } else {
        //get the user with the email
        $sql = "SELECT * FROM users WHERE username = '" . $email . "'";
        $query = $conn->query($sql);
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            //verify password
            if (password_verify($_POST['password'], $row['password'])) {
                //action after a successful login
                //for now just message a successful login
                $_SESSION['success'] = 'Login successful';
                //unset our attempt
                unset($_SESSION['attempt']);
            } else {
                $_SESSION['error'] = 'Password incorrect';
                //this is where we put our 3 attempt limit
                $_SESSION['attempt'] += 1;
                //set the time to allow login if third attempt is reach
                if ($_SESSION['attempt'] == 3) {
                    $_SESSION['attempt_again'] = time() + (5 * 60);
                    //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
                }
            }
        } else {
            // $_SESSION['error'] = 'No account with that username';
            // sign up user 
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES ('" . $email . "', '" . $hashed_password . "')";
        }
    }
} else {
    $_SESSION['error'] = 'Fill up login form first';
}

header('location: login.php');
