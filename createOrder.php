<?php include("connection/db.php");
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
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="createOrder.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Document</title>
    <style>
        /* Add your CSS styles here */
        .dropdown {
            display: none; /* Hide dropdowns by default */
        }
        .dropdown.active {
            display: block; /* Show dropdown when active class is present */
        }
        option{
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
    </style>
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
                    <li class="mainmenu">
                        <a href="dashboard.html"><i class="fas fa-tachometer-alt"></i>DASHBOARD</a>
                    </li>
                    <li class="mainmenu">
                        <a href="reports.html"><i class="fas fa-file"></i>REPORTS</a>
                    </li>
                    <li class="dropdown-toggle mainmenu" onclick="toggleDropdown('products')">
                        <a href="javascript:void(0);" class="mainmenu-link">
                            <i class="fas fa-tag"></i>PRODUCTS <i class="fas fa-angle-down arrow"></i></a>
                        <ul class="dropdown" id="products">
                            <li class="submenu"><a href="viewProduct.html"><i class="fas fa-circle"></i>View Product</a></li>
                            <li class="submenu"><a href="addProduct.html"><i class="fas fa-circle"></i>Add Product</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-toggle mainmenu" onclick="toggleDropdown('orders')">
                        <a href="#"><i class="fas fa-shopping-cart"></i>ORDERS <i class="fas fa-angle-down arrow"></i></a>
                        <ul class="dropdown" id="orders">
                            <li class="submenu"><a href="createOrder.html"><i class="fas fa-circle"></i>Create Order</a></li>
                            <li class="submenu"><a href="viewOrder.html"><i class="fas fa-circle"></i>View Orders</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
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
                <form action="createOrderCode.php" method="post">
                    <div class="main-form">
                        <div class="addproduct">
                            <button class="addproductbtn" onclick="addProduct()">Add Product</button>
                        </div>
                        <div class="addorderarea" id="orderArea">
                            <div class="sentence">
                                <p class="defaultsentence";>No product selected</p>
                            </div>
                           
                        </div>
                        <button class="btn" type="submit"><i class="fas fa-plus"></i> Submit Order</button>
                    </div>
                </form>
                
                
            </div>

            
        </div>
    </div>

    <script>
        function toggleDropdown(id) {
            var dropdown = document.getElementById(id);
            dropdown.classList.toggle("active");
        };
        function addProduct() {
    // Create a new product selection area
    var productSelection = document.createElement("div");
    productSelection.classList.add("product-selection");

    // Add select and quantity elements
    productSelection.innerHTML = `
        
        <div class="product-options" style="display:none;">
            <select class="selectproduct">
                <option selected disabled>Select a product</option>
                <?php foreach ($products as $product): ?>
                <option><?php echo $product?></option>
                <?php endforeach; ?>
                
            </select>
            <div class="quant">
            <label for="quantity">Quantity</label>
            <input type="number" min="0" class="Quantity" id="quantity">
            </div>
        </div>
    `;

    // Append the new product selection area to the order area
    var orderArea = document.getElementById("orderArea");
    orderArea.appendChild(productSelection);

    // Show the product options
    // Show the product options
      var productOptions = productSelection.querySelector(".product-options");
      productOptions.style.display = "flex"; // or "flex" depending on your styling preference

    
    // Hide the default sentence
    var sentence = document.querySelector(".defaultsentence");
    sentence.style.display = "none";
}
    document.addEventListener("DOMContentLoaded", function() {
   
    var form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
       
        event.preventDefault();

        //
    });
});

    </script>
</body>
</html>
