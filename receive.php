<?php
/*
1. The system must have membership-signup functionality including:
a. No duplicate usernames are permitted DONE
b. Username must be an email address DONE
c. Passwords are hashed when stored (i.e., the database does not contain the actual password) DONE
2. There must be a “reset password” function whereby users who forget their passwords can get an email
with a new password in it. Your application must generate a new password and update the user’s
database record with it.
3. After registering, an email is sent to the user with a hyperlink in it. When the user clicks the link, the
account becomes “active” and users can log in. Users cannot login to the system until this link has been
clicked.
4. If a user fails three times in a row to give the correct password for a given username, the account
becomes “locked” and an email is sent to the user DONE
*/

session_start();

if (isset($_POST['resetPassword'])) {
    echo "reset password clicked";
    if (!empty($_POST['email']) && empty($_POST['password'])) {
        // check if user exists is db, if not, send error message
        $inputEmail = $_POST['email'];
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $generatedPass = substr(str_shuffle($data), 0, 6);
        echo $generatedPass;

        $_SESSION['success'] = 'Password reset email sent.';
        mail($inputEmail, 'Password reset', 'Please login with this new password: ' . $generatedPass . '  here http://localhost:8888/assignmentPHP/login.php');

        $save_new_password = "INSERT INTO user (passwordHash) VALUES ('$generatedPass')";
        mysqli_query($mysql, $save_new_password);
        unset($_SESSION['attempt']);
        header('location: login.php');
    } else {
        $_SESSION['error'] = 'Fill email field to reset password.';
        header('location: login.php');
    }
} else if (isset($_POST['login'])) {
    echo "login clicked";
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        echo "email and password not empty";
        $inputEmail = $_POST['email'];
        $inputPassword = $_POST['password'];

        try {
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
                    //check if there are 3 attempts already
                    if ($_SESSION['attempt'] == 3) {
                        $_SESSION['error'] = 'Attempt limit reached, please check email for unlock link.';
                        mail($inputEmail, 'Account locked', 'Please click the link to unlock your account: ' . 'http://localhost:8888/assignmentPHP/unlock.php');
                        header('location: login.php');
                    } else {
                        $_SESSION['attempt'] += 1;
                        $_SESSION['error'] = 'Password incorrect, attempts left: ' . (3 - $_SESSION['attempt']);
                        header('location: login.php');
                    }
                }
            } else {
                echo "user not found";
                $_SESSION['error'] = 'No account with that username, please sign up.';
                header('location: login.php');
            }
        } catch (mysqli_sql_exception $e) {
            echo "could not connect to database";
            error_log($e->getMessage());
        }
    } else {
        $_SESSION['error'] = 'Fill email and password fields to login.';
        header('location: login.php');
    }
} else if (isset($_POST['signup'])) {
    echo "signup clicked";
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        echo "email and password not empty";
        // $inputEmail = $_POST['email'];
        // $inputPassword = $_POST['password'];
        // $hashed_password = password_hash($inputPassword, PASSWORD_DEFAULT);
        // $insert_user = "INSERT INTO user (email, passwordHash) VALUES ('$inputEmail', '$hashed_password')"; //maybe add a active column
        // $mysql->query($insert_user);
        // unset($_SESSION['attempt']);
        header('location: index.php');

        // // fix this, maybe go to another page
        // $_SESSION['success'] = 'Activation link sent to email.';
        // mail($inputEmail, 'Activate user', 'Please click this link to activate your account: _____' . 'http://localhost:8888/assignmentPHP/activateUser.php');
        // header('Location: login.php');
    } else {
        $_SESSION['error'] = 'Fill email and password fields to signup.';
        header('location: login.php');
    }
}

// try {
//     if (!empty($_POST['email']) && !empty($_POST['password'] && isset($_POST['login']))) {
//         $inputEmail = $_POST['email'];
//         $inputPassword = $_POST['password'];

//         if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
//             echo ("$inputEmail is a valid email address");
//         } else {
//             exit("$inputEmail is not a valid email address");
//         }

//         $servername = "containers-us-west-119.railway.app";
//         $username = "root";
//         $password = "yo5sZZTb4tea7SMAl4s2";
//         $database = "railway";
//         $port = 7628;

//         $mysql = new mysqli($servername, $username, $password, $database, $port);
//         if ($mysql === false) {
//             die("ERROR: Could not connect. "
//                 . mysqli_connect_error());
//         } else {
//             echo "Connected to database successfully";
//             echo "Attempts made: " . $_SESSION['attempt'];
//         }

//         //set login attempt if not set
//         if (!isset($_SESSION['attempt'])) {
//             $_SESSION['attempt'] = 0;
//         }

//         //get the user with the email
//         $sql = "SELECT * FROM user WHERE email = '$inputEmail'";
//         $query = $mysql->query($sql);
//         if ($query->num_rows > 0) {
//             echo "user found";
//             $row = $query->fetch_assoc();

//             //verify password
//             if (password_verify($inputPassword, $row['passwordHash'])) {
//                 // echo "password correct";
//                 //action after a successful login
//                 $_SESSION['success'] = 'Login successful';
//                 $_SESSION['email'] = $inputEmail;
//                 //unset attempt
//                 unset($_SESSION['attempt']);

//                 header('location: index.php');
//             } else {
//                 echo "password incorrect";
//                 //check if there are 3 attempts already
//                 if ($_SESSION['attempt'] == 3) {
//                     $_SESSION['error'] = 'Attempt limit reached, please check email for unlock link.';
//                     mail($inputEmail, 'Account locked', 'Please click the link to unlock your account: ' . 'http://localhost:8888/assignmentPHP/unlock.php');
//                     header('location: login.php');
//                 } else {
//                     $_SESSION['attempt'] += 1;
//                     $_SESSION['error'] = 'Password incorrect, attempts left: ' . (3 - $_SESSION['attempt']);
//                     header('location: login.php');
//                 }
//             }
//         } else {
//             echo "user not found";
//             // // $_SESSION['error'] = 'No account with that username';

//             // sign up user 
//             // echo "sign up user";
//             $hashed_password = password_hash($inputPassword, PASSWORD_DEFAULT);
//             $insert_user = "INSERT INTO user (email, passwordHash) VALUES ('$inputEmail', '$hashed_password')";
//             $mysql->query($insert_user);
//             $_SESSION['email'] = $inputEmail;
//             unset($_SESSION['attempt']);
//             header('location: index.php');
//         }
//     } else {
//         $_SESSION['error'] = 'Fill login form first';
//     }
// } catch (mysqli_sql_exception $e) {
//     echo "could not connect to database";
//     error_log($e->getMessage());
// }
