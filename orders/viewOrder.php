<?php include("../connection/db.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/side.css">
    <link rel="stylesheet" href="../css/viewOrder.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
   
    <title>Document</title>
    
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
                    <a href="addproduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Product</a>
                    <a href="viewProduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Products</a>
                </div>
            </div>
            <div class="item">
                <a class="sub-btn orders-btn active"><i class="fas fa-shopping-cart"></i>ORDERS
                    <i class="fas fa-angle-right dropdown"></i>
                </a>
                <div class="sub-menu orders-menu">
                    <a href="createOrder.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Order</a>
                    <a href="viewOrder.html" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Orders</a>
                </div>
            </div>
        </div>
    </div>
        <div class="content">
            <div class="content-top">
                <div class="logout">
                    <i class="fas fa-power-off"></i> Log-out
                </div>
            </div>

            <div class="content-main">
                <div class="title">
                    <i class="fas fa-plus icon"></i> Add Order
                </div>
                <div class="input-container">
                    <input type="date" placeholder="Search by Date..." class="rounded-input">
                    <i class="search-icon fas fa-search"></i>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>OrderID</th>                            
                            <th>Amount</th>
                            <th>created_at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>



                        
                    </tbody>
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
        });


        $('.rounded-input').on('input', function() {
    var selectedDate = $(this).val();
    console.log(selectedDate)

    // Split the selected date string into year, month, and day components
    var parts = selectedDate.split('-');

    // Check if the selected date is valid
    if (parts.length === 3) {
        // Format the date as "year/month/day"
        var formattedDate = parts[0] + '-' + parts[1] + '-' + parts[2];
        console.log(formattedDate)

        $.ajax({
            url: 'searchOrder.php',
            type: 'GET',
            data: { date: formattedDate },
            success: function(response) {
                // Update the table with the search results
                $('.table tbody').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                toastr.error('An error occurred while processing your request.');
            }
        });
    } else {
        console.error('Invalid date format:', selectedDate);
        toastr.error('Invalid date format. Please enter a valid date.');
    }
});

   



    </script>
</body>
</html>
