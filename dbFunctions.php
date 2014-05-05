<?php

session_start();

// Takes a userID and returns the name field

function lookupUserName($userID) {

    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

    if (mysqli_connect_errno()) {

        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {

        $query = "SELECT name FROM Users WHERE userID=$userID";   // jj

        $result = mysqli_query($con, $query);

        if ($result != false) {
            $name = mysqli_fetch_array($result);

            mysqli_close($con);

            return $name['name'];
        } else {
            return NULL;
        }
    }
}

function lookupFacName($facID) {

    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

    if (mysqli_connect_errno()) {

        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {

        $query = "SELECT name FROM Faculties WHERE facID=$facID";

        $result = mysqli_query($con, $query);

        // If result is NULL, currUser is an admin.
        if ($result != false) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            mysqli_close($con);

            return $row['name'];
        } else {
            return null;
        }
    }
}

function lookupFacID($facName) {

    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {

        $query = "SELECT facID FROM Faculties WHERE name=\"$facName\"";

        $result = mysqli_query($con, $query);       
        
        if ($result != false) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            mysqli_close($con);
            return $row['facID'];
        } else {
            return null;
        }
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

