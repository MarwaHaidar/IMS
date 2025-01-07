<?php 
session_start();
include("../connection/db.php");

if(isset($_POST['loginbtn'])){

    if(!empty(trim($_POST['username'])) && !empty(trim($_POST['password']))) {
        $username = trim($_POST['username']);
        $enteredPassword = trim($_POST['password']);

        // $hashedPassword = password_hash($enteredPassword, PASSWORD_DEFAULT);

        // // Print the hashed password for debugging
        // echo "Hashed Password: $hashedPassword <br>";

        // Sanitize user input to prevent SQL injection
        $username = mysqli_real_escape_string($connection, $username);

        $login_query = "SELECT * FROM admin WHERE username='$username' LIMIT 1";
        $login_query_run = mysqli_query($connection, $login_query);

        if(mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_assoc($login_query_run);
            $id = $row['id'];
            $name = $row['name'];
            $username = $row['username'];
            $password = $row['password'];
            
            echo "ID: $id, Name: $name, Username: $username, Password: $password <br>";

            if($enteredPassword==$password) {
                $_SESSION['authenticated'] = TRUE;
                $_SESSION['auth_admin'] = [
                    'adminid' => $id,
                    'username' => $username
                ];
                $redirectPage = "../orders/createOrder.php";
                $_SESSION['status'] = "You are Logged In Successfully!";
                header("Location: $redirectPage");
                exit();
            } else {
                $_SESSION['status'] = "Invalid  password";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "Invalid username";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "All Fields are Mandatory";
        header("Location: login.php");
        exit();
    }
}
?>
