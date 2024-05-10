<?php
include("../connection/db.php");
session_start();

// Get the JSON data from the request
$json_data = file_get_contents('php://input');

// Decode the JSON data into PHP array
$data = json_decode($json_data, true);

if ($data !== null) {
    try {
        $connection->begin_transaction();
        // Get the order ID from the first item in the array
        $orderID = $data[0]['orderID'];

        // Initialize total amount
        $totalAmount = 0;

        // Insert order with orderID
        $orderquery = "INSERT INTO orders (orderID) VALUES ('$orderID')";
        $orderresult = $connection->query($orderquery);

        if (!$orderresult) {
            throw new Exception("Error inserting order: " . $connection->error);
        }

        // Iterate over each product and its quantity
        foreach ($data as $item) {
            $product = $connection->real_escape_string($item['product']); // Escape user input
            $quantity = (int)$item['quantity']; // Ensure quantity is an integer

            // Check if the requested quantity is available in stock
            $stockCheckQuery = "SELECT quantity, id, price FROM products WHERE name = '$product'";
            $stockCheckResult = $connection->query($stockCheckQuery);

            if ($stockCheckResult->num_rows == 0) {
                throw new Exception("Product $product is not found in the stock.");
            }

            $row = $stockCheckResult->fetch_assoc();
            $availableQuantity = (int)$row['quantity'];
            $productID = (int)$row['id'];
            $price = (float)$row['price'];

            if ($quantity > $availableQuantity) {
                throw new Exception("Insufficient stock for product $product.");
            }

            $amount = $price * $quantity;
            $totalAmount += $amount; // Update total amount

            // Insert product details into orderdetails table
            $query = "INSERT INTO orderdetails (order_id, product_id, names, quantity) VALUES ('$orderID', $productID, '$product', $quantity)";
            $result = $connection->query($query);

            if (!$result) {
                throw new Exception("Error inserting order details: " . $connection->error);
            }

            // Update product quantity in products table
            $updateQuery = "UPDATE products SET quantity = quantity - $quantity WHERE name = '$product'";
            $updateResult = $connection->query($updateQuery);

            if (!$updateResult) {
                throw new Exception("Error updating product quantity: " . $connection->error);
            }
        }

        // Update total amount in orders table
        $updateAmountQuery = "UPDATE orders SET amount = $totalAmount WHERE orderID = '$orderID'";
        $updateAmountResult = $connection->query($updateAmountQuery);

        if (!$updateAmountResult) {
            throw new Exception("Error updating order amount: " . $connection->error);
        }

        // Commit transaction
        $connection->commit();
        $connection->close();

        // Send response back to AJAX
        echo json_encode(array("status" => "success", "message" => "Order submitted successfully"));
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $connection->rollback();

        // Send error response back to AJAX
        echo json_encode(array("status" => "error", "message" => $e->getMessage(), "alert" => $e->getMessage()));
    }
} else {
    // If JSON data is not received, send back appropriate response
    echo json_encode(array("status" => "error", "message" => "No data received", "alert" => "No data received"));
}
?>
