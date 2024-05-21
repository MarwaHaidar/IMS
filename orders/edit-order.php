<?php
include('../connection/db.php');
session_start();

// Fetch all products from the database
$productQuery = "SELECT name,price FROM products";
$productResult = $connection->query($productQuery);
$productOptions = '';
while ($row = $productResult->fetch_assoc()) {
    $productName = $row['name'];
    $productprice = $row['price'];
    $productOptions .= "<option value='$productName'>$productName</option>";
}

if (isset($_GET['orderId'])) {
    $orderId = $connection->real_escape_string($_GET['orderId']);

    $query = "SELECT * FROM orderdetails WHERE order_id = '$orderId'";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $orders = array();
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    } else {
        echo "Order not found.";
        exit();
    }

    if (isset($_POST['submit'])) {
        // Get submitted quantities
        $submittedQuantities = $_POST['quantities'];

        // Loop through the submitted quantities
        foreach ($submittedQuantities as $index => $newQuantity) {
            // Retrieve the product ID
            $productId = $orders[$index]['product_id'];

            // Get the existing quantity for this product in the order
            $existingQuantity = $orders[$index]['quantity'];

            // Check if the submitted quantity is greater than the existing quantity
            if ($newQuantity > $existingQuantity) {
                // Calculate the added quantity
                $addedQuantity = $newQuantity - $existingQuantity;

                // Update the quantity for the current product in the order
                $updateQuery = "UPDATE orderdetails SET quantity = '$newQuantity' WHERE order_id = '$orderId' AND product_id = '$productId'";
                $updateResult = $connection->query($updateQuery);

                // Check if the update was successful
                if (!$updateResult) {
                    echo "Error updating product details.";
                    exit();
                }

                // Update the quantity available in the products table
                $updateProductQuery = "UPDATE products SET quantity = quantity - '$addedQuantity' WHERE id = '$productId'";
                $updateProductResult = $connection->query($updateProductQuery);

                // Check if the update was successful
                if (!$updateProductResult) {
                    echo "Error updating product availability.";
                    exit();
                }
            } else if ($newQuantity < $existingQuantity) {
                $returnedQuantity = $existingQuantity - $newQuantity;
                // Update the quantity for the current product in the order
                $updateQuery = "UPDATE orderdetails SET quantity = '$newQuantity' WHERE order_id = '$orderId' AND product_id = '$productId'";
                $updateResult = $connection->query($updateQuery);

                // Check if the update was successful
                if (!$updateResult) {
                    echo "Error updating product details.";
                    exit();
                }

                // Update the quantity available in the products table
                $updateProductQuery = "UPDATE products SET quantity = quantity + '$returnedQuantity' WHERE id = '$productId'";
                $updateProductResult = $connection->query($updateProductQuery);

                // Check if the update was successful
                if (!$updateProductResult) {
                    echo "Error updating product availability.";
                    exit();
                }
            }
        }


       


        // Calculate the total amount for the order
        // $totalOrderAmount = 0;
        // foreach ($orders as $index => $order) {

        //     // Retrieve the quantity and price of each product
        //     $quantity = $order['quantity'];
        //     $price = $order['price'];

        //     // Calculate the amount for this product and add it to the total order amount
        //     $productAmount = $quantity * $price;
        //     $totalOrderAmount += $productAmount;
        // }

        // Update the total amount of the order in the orders table
        // $updateAmountQuery = "UPDATE orders SET Amount = '$totalOrderAmount' WHERE orderID = '$orderId'";
        // $updateAmountResult = $connection->query($updateAmountQuery);

        // if (!$updateAmountResult) {
        //     echo "Error updating total amount.";
        //     exit();
        // }


        $orderquery = "SELECT * FROM orderdetails WHERE order_id = '$orderId'";
    $orderresult = $connection->query($orderquery);
    
    if ($orderresult->num_rows > 0) {
        $totalOrderAmount = 0; // Initialize total order amount
        
        while ($row = $orderresult->fetch_assoc()) {
            $productId = $row['product_id'];
            $productquantity = $row['quantity'];
    
            // Fetch the price of the product
            $pricequery = "SELECT price FROM products WHERE id = '$productId'";
            $priceresult = $connection->query($pricequery);
            
            if ($priceresult->num_rows > 0) {
                $priceData = $priceresult->fetch_assoc();
                $productprice = $priceData['price'];
    
                // Calculate the amount for this product
                $productAmount = $productprice * $productquantity;
    
                // Add the product amount to total order amount
                $totalOrderAmount += $productAmount;
            } else {
                echo "Error fetching product price for product ID: $productId";
                exit();
            }
        }
    
        // Update the total amount in the orders table
        $updateOrderQuery = "UPDATE orders SET Amount = '$totalOrderAmount' WHERE orderID = '$orderId'";
        $updateOrderResult = $connection->query($updateOrderQuery);
        
        if ($updateOrderResult) {
            echo "Total amount updated successfully.";
        } else {
            echo "Error updating total amount in orders table.";
        }
    } else {
        echo "Order not found.";
        exit();
    }
    



        // Redirect back to view order page
        header("Location: viewOrder.php?orderId=$orderId");
        exit();
    }

    



} else {
    echo "Order ID not provided.";
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link real="stylesheet" href="../css/editOrder.css">
    <link rel="stylesheet" href="../css/side.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style>
        .content {
    flex-grow: 1;
    padding: 20px;
    background-color: #ecf0f1;
}

.content h1 {
    margin-top: 0;
}

/* Form Styling */
#edit-order-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.product-section {
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.product-section span {
    font-weight: bold;
}

.product-section label {
    display: inline-block;
    margin: 10px 0 5px;
}

.product-section input[type="number"] {
    width: 50px;
    padding: 5px;
    margin-left: 10px;
}

.delete-product-btn {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    margin-left: 10px;
    border-radius: 3px;
}

.delete-product-btn:hover {
    background-color: #c0392b;
}

input[type="submit"] {
    padding: 10px 20px;
    background-color: #27ae60;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    align-self: flex-start;
}

input[type="submit"]:hover {
    background-color: #229954;
}

/* Toastr Styling */
.toast-success {
    background-color: #27ae60 !important;
}

.toast-error {
    background-color: #e74c3c !important;
}
    </style>
</head>
<body>
<div class="container">
    <div class="side-bar">
        <h1 class="titleims">IMS</h1>
        <h2>Welcome Admin</h2>
        <hr>
        <div class="menu">
            <div class="item"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>DASHBOARD</a></div>
            <div class="item"><a href="reports.php"><i class="fas fa-file"></i>REPORTS</a></div>
            <div class="item">
                <a class="sub-btn"><i class="fas fa-tag"></i>PRODUCTS
                    <i class="fas fa-angle-right dropdown"></i>
                </a>
                <div class="sub-menu">
                    <a href="addproduct.php" class="sub-item">Add Product</a>
                    <a href="viewProduct.php" class="sub-item">View Products</a>
                </div>
            </div>
            <div class="item">
                <a class="sub-btn orders-btn"><i class="fas fa-shopping-cart"></i>ORDERS
                    <i class="fas fa-angle-right dropdown"></i>
                </a>
                <div class="sub-menu orders-menu">
                    <a href="createOrder.php" class="sub-item">Add Order</a>
                    <a href="viewOrder.html" class="sub-item">View Orders</a>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <h1>Edit Order</h1>
        <form id="edit-order-form" action="" method="post">
            <div id="product-sections">
                <?php foreach ($orders as $index => $order): ?>
                    <div class="product-section">
                        <span><?php echo $order['names']; ?></span>
                        <br><br>
                        <label for="quantity<?php echo $index; ?>">Quantity:</label>
                        <input type="number" name="quantities[]" id="quantity<?php echo $index; ?>" value="<?php echo $order['quantity']; ?>">
                        <button type="button" class="delete-product-btn" data-index="<?php echo $index; ?>" data-product-id="<?php echo $order['product_id']; ?>">Delete</button>
                        <input type="hidden" name="productId[]" value="<?php echo $order['product_id']; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="submit" name="submit" value="Save Changes">
        </form>
       
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>    
<script>
    $(document).ready(function(){
        $('.sub-btn').click(function(){
            $(this).next('.sub-menu').slideToggle();
            $(this).find('.dropdown').toggleClass('rotate');
        });
  

    // document.querySelectorAll('.delete-product-btn').forEach(button => {
    //     button.addEventListener('click', function() {
    //         const index = this.getAttribute('data-index');
    //         const productSection = document.querySelector(`#quantity${index}`).parentNode;
    //         productSection.parentNode.removeChild(productSection);
    //     });
    // });

    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('edit-order-form');

        form.addEventListener('submit', function(event) {
            const productSections = document.querySelectorAll('.product-section');
            productSections.forEach(function(section) {
                const productId = section.querySelector('input[name="productId[]"]').value;
                const quantity = section.querySelector('input[name="quantities[]"]').value;
                console.log("Product ID: " + productId + ", Quantity: " + quantity);

            });
        });
    });

    document.querySelectorAll('.delete-product-btn').forEach(button => {
    button.addEventListener('click', function() {
        const index = this.getAttribute('data-index');
        const productId = this.getAttribute('data-product-id');
        
        const urlParams = new URLSearchParams(window.location.search);
        const orderId = urlParams.get('orderId');

        // Perform AJAX request to delete_product.php with orderId and productId
        $.ajax({
            type: 'POST',
            url: 'delete_product.php',
            data: {
                orderId: orderId,
                productId: productId
            },
            success: function(response) {
                // Handle success
                console.log(response);
                // Show success message using Toastr
                toastr.success('Product deleted successfully');
                // Reload the page or update UI as needed
                location.reload();
            
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
                toastr.error('Error deleting product');
            }
        });
    });
});


    });
</script>
</body>
</html>
