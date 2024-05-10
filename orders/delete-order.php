<?php include('../connection/db.php');
session_start();


if(isset($_POST['orderId'])){

    $orderId=$connection->real_escape_string($_POST['orderId']);

    $query="DELETE FROM orders WHERE orderID='$orderId'";

    if($connection->query($query)===TRUE){
        echo "success";

    }
    else{
        echo "Error".$connection->error;
    }}
else{
    echo "Error :OrderID is not provided.";
}

$connection->close();
















?>