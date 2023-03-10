<?php
/*
1. The system must have membership-signup functionality including:
a. No duplicate usernames are permitted DONE
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

// display a login form
// if the user is logged in, redirect to the notes list page?
?>

<?php
session_start();
// echo "Attempts made: " . $_SESSION['attempt'];
unset($_SESSION['success']);
unset($_SESSION['email']);
// unset($_SESSION['attempt']);


?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 panel panel-default" style="padding:20px;">
                <form action="receive.php" method="POST">
                    <p class="text-center" style="font-size:25px;"><b>Login</b></p>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" minlength="7" class="form-control" placeholder="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" minlength="6" class="form-control" placeholder="password">
                    </div>
                    <input type="submit" name="login" class="btn btn-primary" value="Login"></input>&nbsp;&nbsp;&nbsp;
                    <input type="submit" name="signup" class="btn btn-secondary" value="Signup"></input>&nbsp;&nbsp;&nbsp;

                    <input type="submit" name="resetPassword" class="btn btn-info" value="Reset Password"></input>

                </form>
                <?php
                if (isset($_SESSION['error'])) {
                ?>
                    <div class="alert alert-danger text-center" style="margin-top:20px;">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                <?php

                    unset($_SESSION['error']);
                }

                if (isset($_SESSION['success'])) {
                ?>
                    <div class="alert alert-success text-center" style="margin-top:20px;">
                        <?php echo $_SESSION['success']; ?>
                    </div>
                <?php

                    unset($_SESSION['success']);
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>