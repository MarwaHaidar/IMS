<?php include("connection/db.php");
session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="sidebar">

            <div class="logo">
                <h1>IMS</h1>
                <hr>
            </div>

            <nav>
                <ul>
                    <li class="active mainmenu">
                        <a href="dasboard.html"><i class="fas fa-tachometer-alt"></i>DASHBOARD</a>
                    </li>
                    <li class="mainmenu">
                        <a href="reports.html"><i class="fas fa-file"></i>REPORTS</a>
                    </li>
                    <li class="dropdown-toggle mainmenu">
                        <a href="javascript:void(0);" class="mainmenu-link">
                            <i class="fas fa-tag"></i>PRODUCTS <i class="fas fa-angle-down arrow"></i></a>
                        <ul class="dropdown">
                            <li class="submenu"><a href="viewProduct.html"><i class="fas fa-circle"></i>View Product</a></li>
                            <li class="submenu"><a href="addProduct.html"><i class="fas fa-circle"></i>Add Product</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-toggle mainmenu">
                        <a href="#"><i class="fas fa-shopping-cart"></i>ORDERS <i class="fas fa-angle-down arrow"></i></a>
                        <ul class="dropdown">
                            <li class="submenu"><a href="createOrder.html"><i class="fas fa-circle"></i>Create Order</a></li>
                            <li class="submenu"><a href="viewOrder.html"><i class="fas fa-circle"></i>View Orders</a></li>
                        </ul>
                    </li>
                    
                    
                </ul>
            </nav>
        </div>
        <div class="content">
            DASHBOARD
        </div>
    </div>


</body>
</html>