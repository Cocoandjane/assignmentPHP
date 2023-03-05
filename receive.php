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

try {

    session_start();

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $inputEmail = $_POST['email'];
        $inputPassword = $_POST['password'];

        // if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
        //     echo ("$inputEmail is a valid email address");
        // } else {
        //     exit("$inputEmail is not a valid email address");
        // }

        $servername = "containers-us-west-119.railway.app";
        $username = "root";
        $password = "yo5sZZTb4tea7SMAl4s2";
        $database = "railway";
        $port = 7628;

        $mysql = new mysqli($servername, $username, $password, $database, $port);
        if ($mysql === false) {
            die("ERROR: Could not connect. "
                . mysqli_connect_error());
        } else {
            echo "Connected to database successfully";
            echo "Attempts made: " . $_SESSION['attempt'];
        }

        //set login attempt if not set
        if (!isset($_SESSION['attempt'])) {
            $_SESSION['attempt'] = 0;
        }

        //check if there are 3 attempts already
        if ($_SESSION['attempt'] == 3) {
            // $_SESSION['error'] = 'Attempt limit reach';
            // header('location: login.php');
        } else {
            //get the user with the email
            $sql = "SELECT * FROM user WHERE email = '$inputEmail'";
            $query = $mysql->query($sql);
            if ($query->num_rows > 0) {
                echo "user found";
                $row = $query->fetch_assoc();

                //verify password
                if (password_verify($inputPassword, $row['passwordHash'])) {
                    // echo "password correct";
                    //action after a successful login
                    $_SESSION['success'] = 'Login successful';
                    $_SESSION['email'] = $inputEmail;
                    //unset attempt
                    unset($_SESSION['attempt']);

                    header('location: index.php');
                } else {
                    echo "password incorrect";
                    $_SESSION['error'] = 'Password incorrect';
                    $_SESSION['attempt'] += 1;
                    // //set the time to allow login if third attempt is reach
                    // if ($_SESSION['attempt'] == 3) {
                    //     $_SESSION['attempt_again'] = time() + (5 * 60);
                    //     //note 5*60 = 5mins, 60*60 = 1hr, to set to 2hrs change it to 2*60*60
                    // }
                    header('location: login.php');
                }
            } else {
                echo "user not found";
                // // $_SESSION['error'] = 'No account with that username';

                // sign up user 
                // echo "sign up user";
                $hashed_password = password_hash($inputPassword, PASSWORD_DEFAULT);
                $insert_user = "INSERT INTO user (email, passwordHash) VALUES ('$inputEmail', '$hashed_password')";
                $mysql->query($insert_user);
                // if ($mysql->query($insert_user) === TRUE) {
                //     echo "New user created successfully";
                // }
                $_SESSION['email'] = $inputEmail;
                header('location: index.php');
            }
        }
    } else {
        $_SESSION['error'] = 'Fill login form first';
    }
} catch (mysqli_sql_exception $e) {
    echo "could not connect to database";
    error_log($e->getMessage());
}
