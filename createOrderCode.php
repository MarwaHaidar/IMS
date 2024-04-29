<?php
include("connection/db.php");
session_start();

// Get the JSON data from the request
$json_data = file_get_contents('php://input');

// Decode the JSON data into PHP array
$data = json_decode($json_data, true);

if ($data !== null) {
    try {
        $connection->begin_transaction();

        // Iterate over each product and its quantity
        foreach ($data as $item) {
            $product = $connection->real_escape_string($item['product']); // Escape user input
            $quantity = (int)$item['quantity']; // Ensure quantity is an integer

            // Insert data into the database
            $query = "INSERT INTO orders (names, quantity) VALUES ('$product', $quantity)";
            $result = $connection->query($query);

            if (!$result) {
                throw new Exception("Error inserting order: " . $connection->error);
            }
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
        echo json_encode(array("status" => "error", "message" => $e->getMessage()));
    }
} else {
    // If JSON data is not received, send back appropriate response
    echo json_encode(array("status" => "error", "message" => "No data received"));
}
?>
