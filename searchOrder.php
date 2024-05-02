<?php
include('connection/db.php');


session_start();


// Handle date selection from GET request
if(isset($_GET['date'])) {
    // Sanitize and get the selected date
    $formattedDate = $connection->real_escape_string($_GET['date']);

 
    // Construct SQL query to retrieve orders for the selected date
    $query = "SELECT * FROM orders WHERE created_at = '$formattedDate'";
    
    // Execute the query
    $result = $connection->query($query);
    
    // Check if there are any orders found
    if ($result->num_rows > 0) {
        // Output table rows for each order
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td></td>";
            echo "<td>" . $row['orderID'] . "</td>";
            echo "<td>" . $row['Amount'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td><button
            style='
            padding:10px;
            background-color:grey;
            color:white;
            border:none;
            margin-right:15px;
            border-radius:5px'
             class='view-order-btn' data-order-id='" . $row['orderID'] . "'>View</button> 
            <button style='
            padding: 10px; 
            background-color:#323232; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 5px; 
            margin-right: 15px; 
            padding-left: 15px; 
            padding-right: 15px;' 
            class='edit-btn' 
            data-order-id='" . $row['orderID'] . "'>Edit</button>
            <button style='
            padding: 10px; 
            background-color: darkred; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 5px;' 
            class='delete-btn' 
            data-order-id='" . $row['orderID'] . "'>Delete</button></td>";
            echo "</tr>";
        }
    } else {
        // No orders found for the selected date
        echo "<tr><td colspan='5'>No orders found for the selected date.</td></tr>";
    }
}
?>
