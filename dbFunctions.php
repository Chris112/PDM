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

function lookupProjID($projName) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "SELECT projID FROM Projects WHERE name=\"$projName\"";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_close($con);
        return $row['projID'];
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

function lookupUserPermission($userID){
     $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $query = "SELECT permissions FROM User_Projects WHERE userID=$userID";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_close($con);
        return $data['permissions'];
    }
}

function addFileToProJ($projName, $newFileSize, $currUsed, $currFree) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
        $newUsed = $currUsed + $newFileSize;
        $newFree = $currFree - $newFileSize;

        // update project used
        $query1 = "UPDATE Projects SET used_space=$newUsed WHERE name=\"$projName\"";
        $result1 = mysqli_query($con, $query1);

        // update project free
        $query2 = "UPDATE Projects SET free_space=$newFree WHERE name=\"$projName\"";
        $result2 = mysqli_query($con, $query2);

        mysqli_close($con);
    }
}

function removeFileFromProj($projName, $currUsed, $currFree, $filename) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');

    // get file size
    $query1 = "SELECT size FROM Files WHERE filename=\"$filename\"";
    $result1 = mysqli_query($con, $query1);
    $fileSize = mysqli_fetch_array($result1, MYSQLI_ASSOC);

    $newUsed = $currUsed - $fileSize['size'];
    $newFree = $currFree + $fileSize['size'];

    // update used_space
    $query2 = "UPDATE Projects SET used_space=$newUsed WHERE name=\"$projName\"";
    $result2 = mysqli_query($con, $query2);
    //update free_space
    $query3 = "UPDATE Projects SET free_space=$newFree WHERE name=\"$projName\"";
    $result3 = mysqli_query($con, $query3);

    mysqli_close($con);
}

function testIfPI($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    $query = "SELECT * FROM Projects";
    $result = mysqli_query($con, $query);
    $count = 0;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $count++;
    }
    mysqli_close($con);
    return $count;
}

function getApproverIDOfFac($facID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    $query = "SELECT approver FROM Faculties WHERE facID=$facID";
    $result = mysqli_query($con, $query);
    if ($result) {
        $approver = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_close($con);
        return $approver['approver'];
    } else {
        echo "eee";
    }
}

function giveRead($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    // get current permission level
    $query1 = "SELECT permissions FROM User_Projects WHERE userID=$userID";
    $result1 = mysqli_query($con, $query1);
    $data = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    $perm = $data['permissions'];

    // if user doesn't have read, give it to them, otherwise do nothing
    if ($perm == 1 OR $perm == 3) {
        return;
    } else if ($perm == 0) {
        // give user read
        $perm = 1;
        $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
        $result2 = mysqli_query($con, $query2);
        if (!$result2) {
            echo "ERROR GIVING A USER READ.<br>";
        }
    } else if ($perm == 2) {
        // give user read
        $perm = 3;
        $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
        $result2 = mysqli_query($con, $query2);
        if (!$result2) {
            echo "ERROR GIVING A USER READ.<br>";
        }
    }
}

function takeRead($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    // get current permission level
    $query1 = "SELECT permissions FROM User_Projects WHERE userID=$userID";
    $result1 = mysqli_query($con, $query1);
    $data = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    $perm = $data['permissions'];
    $oldPerm = $perm;

    // if user has read, take it from them, otherwise do nothing
    if ($perm == 1 OR $perm == 3) {

        // take users read
        $perm--;
        $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
        $result2 = mysqli_query($con, $query2);
        if (!$result2) {
            echo "ERROR TAKING A USERS READ.<br>";
        }
    } else if ($perm == 0 OR $perm == 2) {
        return;
    }
}

function giveWrite($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    // get current permission level
    $query1 = "SELECT permissions FROM User_Projects WHERE userID=$userID";
    $result1 = mysqli_query($con, $query1);
    $data = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    $perm = $data['permissions'];

    // if user doesn't have write, give it to them, otherwise do nothing
    if ($perm == 2 OR $perm == 3) {
        return;
    } else if ($perm == 0) {
        // give user just read
        $perm = 2;
        $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
        $result2 = mysqli_query($con, $query2);
        if (!$result2) {
            echo "ERROR GIVING A USER WRITE.<br>";
        }
    } else if ($perm == 1) {
        // give user just read
        $perm = 3;
        $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
        $result2 = mysqli_query($con, $query2);
        if (!$result2) {
            echo "ERROR GIVING A USER WRITE.<br>";
        }
    }
}

function takeWrite($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    // get current permission level
    $query1 = "SELECT permissions FROM User_Projects WHERE userID=$userID";
    $result1 = mysqli_query($con, $query1);
    $data = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    $perm = $data['permissions'];
    // if user has read, take it from them, otherwise do nothing
    if ($perm == 2) {

        // take users read
        $perm = 0;
        $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
        $result2 = mysqli_query($con, $query2);
        if (!$result2) {
            echo "ERROR TAKING A USERS WRITE.<br>";
        }
    } else if ($perm == 0 OR $perm == 1) {
        return;
    } else if ($perm == 3) {
        // take users read
        $perm = 2;
        $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
        $result2 = mysqli_query($con, $query2);
        if (!$result2) {
            echo "ERROR TAKING A USERS WRITE.<br>";
        }
    }
}

function giveWriteTakeRead($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    $perm = 2;
    $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
    $result2 = mysqli_query($con, $query2);
    if (!$result2) {
        echo "ERROR TAKING A USERS WRITE.<br>";
    }
}

function giveReadTakeWrite($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    $perm = 1;
    $query2 = "UPDATE User_Projects SET permissions=$perm WHERE userID=$userID";
    $result2 = mysqli_query($con, $query2);
    if (!$result2) {
        echo "ERROR TAKING A USERS WRITE.<br>";
    }
}

function getSiteLevel($userID) {
    $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');
    $query = "SELECT site_level FROM Users WHERE userID=$userID";
    $result = mysqli_query($con, $query);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $data['site_level'];
}
