<?php
include('../connection/db.php');
session_start();

if(isset($_POST['productId']) && isset($_POST['orderId'])) {
    $productId = $connection->real_escape_string($_POST['productId']);
    $orderId = $connection->real_escape_string($_POST['orderId']);

    // Retrieve the quantity for the specific product in the order
    $quantityQuery = "SELECT quantity FROM orderdetails WHERE order_id = '$orderId' AND product_id = '$productId'";
    $quantityResult = $connection->query($quantityQuery);

    if ($quantityResult && $quantityResult->num_rows > 0) {
        $row = $quantityResult->fetch_assoc();
        $refundedQuantity = $row['quantity'];

        // Update the products table by adding the refunded quantity for the product
        $updateProductQuery = "UPDATE products SET quantity = quantity + $refundedQuantity WHERE id = '$productId'";
        $updateProductResult = $connection->query($updateProductQuery);

        if (!$updateProductResult) {
            echo json_encode(array('success' => false, 'message' => 'Error updating product quantity.'));
            exit; // Stop further execution
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error retrieving product quantity.'));
        exit; // Stop further execution
    }

    // Delete the product from the order
    $deleteQuery = "DELETE FROM orderdetails WHERE order_id = '$orderId' AND product_id = '$productId'";
    $deleteResult = $connection->query($deleteQuery);

    // Check if the deletion was successful
    if ($deleteResult) {
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
            
            if (!$updateOrderResult) {
                echo json_encode(array('success' => false, 'message' => 'Error updating order total amount.'));
                exit; // Stop further execution
            }
        } 
        
        // Check if there are any remaining products in the order
        $checkRemainingProductsQuery = "SELECT COUNT(*) AS remaining_products FROM orderdetails WHERE order_id = '$orderId'";
        $remainingProductsResult = $connection->query($checkRemainingProductsQuery);
        $remainingProductsRow = $remainingProductsResult->fetch_assoc();
        $remainingProductsCount = $remainingProductsRow['remaining_products'];

        if ($remainingProductsCount == 0) {
            // If no remaining products, remove the order from the orders table
            $deleteOrderQuery = "DELETE FROM orders WHERE orderID = '$orderId'";
            $deleteOrderResult = $connection->query($deleteOrderQuery);

            if (!$deleteOrderResult) {
                echo json_encode(array('success' => false, 'message' => 'Error deleting order.'));
                exit; // Stop further execution
            }
        }

        echo json_encode(array('success' => true));
    } else {
        // Send an error response
        echo json_encode(array('success' => false, 'message' => 'Error deleting product.'));
    }
} else {
    // Send an error response if product ID or order ID is not provided
    echo json_encode(array('success' => false, 'message' => 'Product ID or order ID not provided.'));
}
?>
