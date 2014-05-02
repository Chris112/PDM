<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
// Create connection
        $con = mysqli_connect('localhost', 'samcalab_chriswb', 'uz,vt78?zYpwu*CV6', 'samcalab_uniproject');


// Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        echo "UserID : name : access_level : reg_date : password : email : proj_name";
        echo "<br>";
        $result = mysqli_query($con, "SELECT * FROM Users");
        while ($row = mysqli_fetch_array($result)) {
            echo $row['userID'] . " " . $row['name'] . " " . $row['access_level'] . " " .
            $row['reg_date'] . " " . $row['password'] . " " . $row['email'] . " " . $row['proj_name'];
            echo "<br>";
        }


        // create some fake user to insert
        $name = "aneesh";
        $userID = 4;
        $password = "password";
        $email = "aneesh@turtles.com";
        $proj_name = 'PDM';
        //createUser($con, $password, 1, $name, $email);
        //removeUser($con, $name);
		$res = login($con, $userID, $password);
        if (res == 0){
		    echo 'logged in';
		} else {
		    echo 'failed to login';
		}


        mysqli_close($con);

        function createUser($con, $password, $access_level, $name, $email) {
            $result = mysqli_query($con, "INSERT INTO Users (password, access_level, reg_date, name, email)
VALUES ('$password', '$access_level', now(), '$name', '$email')");

            if ($result) {
                echo "Successfully inserted " . $name . " into Users.<br>";
            } else {
                echo "Failed to enter " . $name . " into Users.<br>";
            }
        }

        function removeUser($con, $name) {
            mysqli_query($con, "DELETE FROM Users WHERE name='$name'");
            echo "deleted " . $name . " from db." . "<br>";
        }

        function login($con, $userID, $password) {
            $query = "SELECT * FROM Users WHERE userID = '$userID' AND password = '$password'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($row['userID'] == $userID && $row['password'] == $password) {
                echo "Successfully logged in.<br>";
            } else {
                echo "Failed to login.<br>";
            }
        }
        ?>
    </body>
</html>


</body>
</html>
