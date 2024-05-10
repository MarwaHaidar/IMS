<?php 

include('../connection/db.php');


session_start();


$htmlTable = "";

// Check if Orderid is set in the GET request
if(isset($_GET['orderId'])){
  
    $orderId = $connection->real_escape_string($_GET['orderId']);

    
    $query = "SELECT * FROM orderdetails WHERE order_id='$orderId'";

    
    $result = $connection->query($query);

   
    if ($result->num_rows > 0) {
        
        $htmlTable .= "<table border='1'>";
        $htmlTable .= "<tr><th>Product ID</th><th>Product Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";

       
        while($row = $result->fetch_assoc()) {
          
            $productId = $row['product_id'];
            $quantity = $row['quantity'];

         
            $productQuery = "SELECT * FROM products WHERE id='$productId'";
            $productResult = $connection->query($productQuery);

         
            if ($productResult->num_rows > 0) {
               
                $productData = $productResult->fetch_assoc();

               
                $price = $productData['price'];
                $total = $price * $quantity;

                
                $htmlTable .= "<tr>";
                $htmlTable .= "<td>" . $productId . "</td>";
                $htmlTable .= "<td>" . $productData['name'] . "</td>"; // Assuming product name is stored in the products table
                $htmlTable .= "<td>" . $quantity . "</td>";
                $htmlTable .= "<td>" . $price . "</td>";
                $htmlTable .= "<td>" . $total . "</td>";
                $htmlTable .= "</tr>";
            } else {
                
                $htmlTable .= "<tr><td colspan='5'>Product details not found for product ID: $productId</td></tr>";
            }
        }

       
        $htmlTable .= "</table>";
    } else {
        
        $htmlTable = "No products found for the given Order ID.";
    }
} else {
    
    $htmlTable = "Order ID not provided.";
}


$connection->close();
// include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <style>
    table {
        width: 95%;
        border-collapse: collapse;
        margin: 30px auto;
    
    }


    table th,
    table td {
        padding: 0.75rem;
        vertical-align: top;   
        text-align: center;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6; 
    }
    #order-details-overlay{
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        margin-top:5%;
    }

    </style>
</head>
<body>
<div id="order-details-overlay" >
    <h2>Order Details</h2>
    
    <?php 
        
        echo $htmlTable;
    ?>
    </div>
</body>
</html>
