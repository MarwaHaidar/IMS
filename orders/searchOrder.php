<?php
include('../connection/db.php');


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
            border-radius:5px;'
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>View Order</title>
 
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        // Event delegation for delete button
        $(document).on('click', '.delete-btn', function(){
            let orderId = $(this).data('order-id');
            let button = $(this); // Store reference to $(this)
            if(confirm("Are you sure you want to delete this order?")){
                $.ajax({
                    url: 'delete-order.php',
                    type: 'POST',
                    data: {orderId: orderId},
                    success: function(response){
                        toastr.success('Order deleted successfully.')
                        button.closest('tr').remove(); // Use the stored reference
                    },
                    error: function(xhr, status, error){
                        console.error(xhr.responseText);
                        toastr.error('An error has occurred while deleting the order.')
                    }
                });
            }
        });

        // Event delegation for view order button
        $(document).on('click', '.view-order-btn', function(){
            let orderId = $(this).data('order-id');
            $.ajax({
                url: 'view-orderdetails.php',
                type: 'GET',
                data: {orderId: orderId}, // Use 'Orderid' instead of 'orderId'
                success: function(response){
                    console.log("Order viewed successfully.");
                    window.location=`http://localhost/IMS/orders/view-orderdetails.php?orderId=${orderId}`
                    // Assuming you have an overlay element with id "order-details-overlay"
                    // $('#order-details-overlay').html(response); // Update the HTML content of the overlay with the response data
                    // $('#order-details-overlay').show(); // Show the overlay
                    

                },
                error: function(xhr, status, error){
                    console.error(xhr.responseText);
                    toastr.error('An error has occurred while viewing the order.')
                }
            });
        });

        $(document).on('click','.edit-btn',function(){
        let orderId = $(this).data('order-id');
        $.ajax({
            url:'edit-order.php',
            method:'GET',
            data: {orderId:orderId},
            success: function(response){
                    console.log("Order edited successfully.");
                    window.location=`http://localhost/IMS/orders/edit-order.php?orderId=${orderId}`
                 
                    

                },
                error: function(xhr, status, error){
                    console.error(xhr.responseText);
                    toastr.error('An error has occurred while editing the order.')
                }
            
        })





    })
    });



</script>

    
    
</body>
</html>

