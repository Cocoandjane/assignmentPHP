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
$servername = "containers-us-west-40.railway.app";
$username = "root";
$password = "1rfGgzegoG0Ld4sSa6BP";
$database = "railway";
$port = 5678;
$headers = 'From: php.mailing.test@gmail.com' . "\r\n" . 
           'MIME-Version: 1.0' . "\r\n" .
           'Content-Type: text/html; charset=utf-8';
// include 'dbConfig.php';

try {
    $mysql = new mysqli($servername, $username, $password, $database, $port);
    if ($mysql === false) {
        die("ERROR: Could not connect. "
            . mysqli_connect_error());
    } else {
        echo "Connected to database successfully";
        // echo "Attempts made: " . $_SESSION['attempt'];
    }
} catch (mysqli_sql_exception $e) {
    throw $e;
}


if (isset($_POST['resetPassword'])) {
    echo "reset password clicked";
    if (!empty($_POST['email']) && empty($_POST['password'])) {
        try {
            $inputEmail = $_POST['email'];

            //check if user exists in db
            $sql = "SELECT * FROM user WHERE email = '$inputEmail'";
            $query = $mysql->query($sql);
            if ($query->num_rows == 0) {
                echo "user not found";
                $_SESSION['error'] = 'User does not exists, please sign up.';
                header('Location: login.php');
            } else {
                echo "user found";
                $row = $query->fetch_assoc();
                // var_dump($row);
                if ($row['active'] != 1) {
                    echo "user not active";
                    $_SESSION['error'] = 'User is not active, please activate your account.';
                    header('Location: login.php');
                } else {
                    echo "user active";
                    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
                    $generatedPass = substr(str_shuffle($data), 0, 6);
                    echo $generatedPass;
                    $hashed_password = password_hash($generatedPass, PASSWORD_DEFAULT);
                    $save_new_password = "UPDATE user SET passwordHash = '$hashed_password' WHERE email = '$inputEmail'";
                    $mysql->query($save_new_password);
    
                    unset($_SESSION['attempt']);
                    $_SESSION['error'] = 'Password reset email sent.';
                    mail($inputEmail, 'Password reset', 'Please login with this new password: ' . $generatedPass . '  here http://localhost:8888/assignmentPHP/login.php', $headers);
                    header('Location: login.php');
                }
            }

        } catch (mysqli_sql_exception $e) {
            echo "could not connect to database";
            error_log($e->getMessage());
        }
    } else {
        $_SESSION['error'] = 'Fill email field to reset password.';
        header('Location: login.php');
    }
} else if (isset($_POST['login'])) {
    echo "login clicked";
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        echo "email and password not empty";
        $inputEmail = $_POST['email'];
        $inputPassword = $_POST['password'];

        try {
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
                // password_verify($inputPassword, $row['passwordHash']) && $row['active'] == 1
                if (password_verify($inputPassword, $row['passwordHash'])) {
                    // echo "password correct";
                    //action after a successful login
                    $_SESSION['success'] = 'Login successful';
                    $_SESSION['email'] = $inputEmail;
                    $_SESSION['last_activity'] = time();
                    unset($_SESSION['attempt']);
                    header('Location: index.php');
                } else {
                    echo "password incorrect";
                    //check if there are 3 attempts already
                    if ($_SESSION['attempt'] == 3) {
                        $_SESSION['error'] = 'Attempt limit reached, please check email for unlock link.';
                        mail($inputEmail, 'Account locked', 'Please click the link to unlock your account: ' . 'http://localhost:8888/assignmentPHP/unlock.php', $headers);
                        header('Location: login.php');
                    } else {
                        $_SESSION['attempt'] += 1;
                        $_SESSION['error'] = 'Password incorrect, attempts left: ' . (3 - $_SESSION['attempt']);
                        header('Location: login.php');
                    }
                }
            } else {
                echo "user not found";
                $_SESSION['error'] = 'No account with that username, please sign up.';
                header('Location: login.php');
            }
        } catch (mysqli_sql_exception $e) {
            echo "could not connect to database";
            error_log($e->getMessage());
        }
    } else {
        $_SESSION['error'] = 'Fill email and password fields to login.';
        header('Location: login.php');
    }
} else if (isset($_POST['signup'])) {
    echo "signup clicked";
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        // echo "email and password not empty";

        try {
            $newEmail = $_POST['email'];
            $newPassword = $_POST['password'];

            //check if user exists in db
            $sql = "SELECT * FROM user WHERE email = '$newEmail'";
            $query = $mysql->query($sql);
            if ($query->num_rows > 0) {
                // echo "user found";
                $_SESSION['error'] = 'User already exists, please use a different email or login.';
                header('Location: login.php');
            } else {
                $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
                $insert_user = "INSERT INTO user (email, passwordHash) VALUES ('$newEmail', '$hashed_password')";
                $mysql->query($insert_user);

                // $_SESSION['email'] = $newEmail;
                // unset($_SESSION['attempt']);
                // header('Location: index.php');

                // fix this, also y only error messages show up
                $_SESSION['error'] = 'Activation link sent to email.';
                mail($newEmail, 'Activate user', 'Please click this link to activate your account: http://localhost:8888/assignmentPHP/activateUser.php?email=' . $newEmail, $headers);
                header('Location: login.php');
            }
        } catch (mysqli_sql_exception $e) {
            echo "could not connect to database";
            error_log($e->getMessage());
        }
    } else {
        $_SESSION['error'] = 'Fill email and password fields to signup.';
        header('Location: login.php');
    }
}

?>