<?php

session_start();



//test

$con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
$userID = $_POST['userID'];
$password = $_POST['password'];

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$query = "SELECT * FROM Users WHERE userID = '$userID' AND password = '$password'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

// removing back end of website while updating database
if ($row['userID'] == $userID && $row['password'] == $password) {
    $_SESSION['currUser'] = $row;
    $_SESSION[$access] = "access";
    header('Location: projectSelection.php');
} else {
    header('Location: login.php');
}


?>