<?php
session_start();
include 'dbFunctions.php';
include 'commonElements.php';
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <meta name="description" content="Login page for Storage System">
        <meta name="author" content="Dons Squad">
        <link rel="shortcut icon" href="assets/icons/icon.ico">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom styles for footer -->
        <link href="css/sticky-footer-navbar.css" rel="stylesheet">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login</title>
    </head>
    <body>



        <!-- Header -->
        <div class="container">
            <div class="page-header panel-primary">
                <div align="center">
                    <h1>The Dons Squad <small> <br>Storage Manager</h1>
                            </div>
                            </div>



                            <!-- Login form -->
                            <div class="container" style="width:300px">
                                <form class="form-signin" role="form" name ="login" id = "loginBox" method = "POST" action = checkLogin.php>
                                    <h2 class="form-signin-heading">Please sign in</h2>
                                    <input type="text" class="form-control" placeholder="User ID" name="userID" required autofocus/>
                                    <input type="password" class="form-control" placeholder="Password" required name="password"/>
                                    <label class="checkbox">
                                        <input type="checkbox" value="remember-me" > Remember my UserID
                                    </label>
                                    <button class="btn btn-lg btn-primary btn-block" type="submit" name="Submit1" value="Login">Sign in</button>
                                </form>
                            </div> 	
                            </div><!-- /container -->



                            <?php displayFooter(); ?>











                            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                            <script src="js/jquery-1.11.0.min.js"></script>
                            <script src="js/bootstrap.min.js"></script>
                            </body>
                            </html>
