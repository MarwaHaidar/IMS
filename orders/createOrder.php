<?php
include("../connection/db.php");
session_start();

$stmt = $connection->prepare("SELECT name FROM products");
$stmt->execute();
$stmt->bind_result($productName);

$products = array();

// Fetch products and store them in an array
while ($stmt->fetch()) {
    $products[] = $productName;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/side.css">
    <link rel="stylesheet" href="../css/createOrder.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Document</title>
    <style>
        option {
            font-size: 15px !important;
        }
        .quant label {
            margin-right: 10px;
            font-weight: 400;
        }
        .quant input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 50px;
        }
        .product-selection {
            margin-bottom: 15px;
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
                <!-- <div class="item"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>DASHBOARD</a></div>
                <div class="item"><a href="reports.php"><i class="fas fa-file"></i>REPORTS</a></div> -->
                <div class="item">
                    <a class="sub-btn"><i class="fas fa-tag"></i>PRODUCTS
                        <i class="fas fa-angle-right dropdown"></i>
                    </a>
                    <div class="sub-menu">
                        <a href="../addproduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Product</a>
                        <a href="../viewProduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Products</a>
                    </div>
                </div>
                <div class="item">
                    <a class="sub-btn orders-btn active"><i class="fas fa-shopping-cart"></i>ORDERS
                        <i class="fas fa-angle-right dropdown"></i>
                    </a>
                    <div class="sub-menu orders-menu">
                        <a href="createOrder.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Order</a>
                        <a href="viewOrder.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Orders</a>
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
                <div class="addproduct" style="margin:10px;">
                    <button class="addproductbtn" onclick="addProduct()">Add Product</button>
                </div>
                <form action="createOrderCode.php" method="post" class="orderform">
                    <div class="main-form">
                        <div class="addorderarea" id="orderArea">
                            <div class="sentence">
                                <p class="defaultsentence">No product selected</p>
                            </div>
                        </div>
                        <button class="btn" type="submit" id="submitt" name="submitbtn"><i class="fas fa-plus"></i> Submit Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.sub-btn').click(function() {
                $(this).next('.sub-menu').slideToggle();
                $(this).find('.dropdown').toggleClass('rotate');
            });
        });

        function initializeSearchableDropdowns() {
            $('.searchable-dropdown').select2({
                placeholder: 'Search for a product',
                allowClear: true
            });
        }

        function addProduct() {
            var productSelection = document.createElement("div");
            productSelection.classList.add("product-selection");

            var productId = "product_" + Date.now();
            var quantityId = "quantity_" + Date.now();

            productSelection.innerHTML = `
                <div class="product-options">
                    <select class="selectproduct searchable-dropdown" name='products[]'>
                        <option selected disabled>Select a product</option>
                        <?php foreach ($products as $product): ?>
                        <option><?php echo $product; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="quant">
                        <label for="${quantityId}">Quantity</label>
                        <input type="number" min="0" class="Quantity" id="${quantityId}" name='quantities[]'>
                    </div>
                </div>
            `;

            var orderArea = document.getElementById("orderArea");
            orderArea.appendChild(productSelection);

            initializeSearchableDropdowns();

            var sentence = document.querySelector(".defaultsentence");
            if (sentence) {
                sentence.style.display = "none";
            }
        }

        $(document).ready(function() {
            initializeSearchableDropdowns();

            var form = document.querySelector(".orderform");
            form.addEventListener("submit", function(event) {
                event.preventDefault();
                var orderID = 'ID' + (Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000);
                orderID = orderID.substring(0, 6);

                var productsArray = [];
                var productOptions = document.querySelectorAll(".product-selection");
                productOptions.forEach(function(option) {
                    var product = option.querySelector(".selectproduct").value;
                    var quantity = option.querySelector(".Quantity").value;
                    productsArray.push({ orderID: orderID, product: product, quantity: quantity });
                });

                var jsonData = JSON.stringify(productsArray);

                $.ajax({
                    url: 'createOrderCode.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: jsonData,
                    success: function(response) {
                        console.log('Order submitted successfully.');
                        console.log(jsonData);
                        response = JSON.parse(response);
                        if (response.status === "success") {
                            toastr.success(response.message);
                            form.reset();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        toastr.error('An error occurred while processing your request.');
                    }
                });
            });
        });
    </script>
</body>
</html>