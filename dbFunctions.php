<?php
session_start();

// Takes a userID and returns the name field
function lookupUserName($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "SELECT name FROM Users WHERE userID=$userID";
        $result = mysqli_query($con, $query);
        $name = mysqli_fetch_array($result);
        mysqli_close($con);
        return $name['name'];
    }
}

function lookupFacName($facID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "SELECT name FROM Faculties WHERE facID=$facID";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_close($con);
        return $row['name'];
    }
}

function lookupProjName($projID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "SELECT name FROM Projects WHERE projID=$projID";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_close($con);
        return $row['name'];
    }
}

// input userID and returns the date they registered in the database
function lookupUserRegDate($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "SELECT reg_date FROM Users WHERE userID=$userID";
        $result = mysqli_query($con, $query);
        $reg_date = mysqli_fetch_array($result);
        mysqli_close($con);
        return $reg_date['reg_date'];
    }
}
 
