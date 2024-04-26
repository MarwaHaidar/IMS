<?php include("../connection/db.php");
session_start();?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="login.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+6F5wlqyJhczP3/cUJGMEmq0sGshI6hFfY8N4QT" crossorigin="anonymous">
    
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1>IMS</h1>
            <h3>Inventory Management System</h3>

        </div>
        <div class="loginbody">
            <form action="logincode.php" method="post">
                
                <div>
                    <label for="username">Username</label><br>
                    <input type="text" name="username" placeholder="username">
                </div>
                <div>
                    <label for="password">Password</label><br>
                    <input type="text" name="password" placeholder="password">
                </div>
                
                <button class="loginbtn" name="loginbtn">Login</button>
                
            </form>
            <div class="message">
            <?php     
                    if(isset($_SESSION['status'])){
                        ?>
                    <div class="alert alert-success mt-3">
                        <h6><?= $_SESSION['status'];?></h6>
                    </div>
                    
                    <?php
                    unset($_SESSION['status']);
                }
               ?>
               </div>

        </div>
      
    </div>
    
</body>
</html>