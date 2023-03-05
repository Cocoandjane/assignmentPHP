<?php
session_start();
unset($_SESSION['attempt']);
header('Location: login.php');

?>