<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/side.css">
    <link rel="stylesheet" href="css/addProduct.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <div class="item  active">
            <a class="sub-btn active"><i class="fas fa-tag"></i>PRODUCTS
                <i class="fas fa-angle-right dropdown"></i>
            </a>
            <div class="sub-menu">
                <a href="./addproduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Product</a>
                <a href="./viewProduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Products</a>
            </div>
        </div>
        <div class="item">
            <a class="sub-btn orders-btn"><i class="fas fa-shopping-cart"></i>ORDERS
                <i class="fas fa-angle-right dropdown"></i>
            </a>
            <div class="sub-menu orders-menu">
                        <a href="orders/createOrder.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Order</a>
                        <a href="orders/viewOrder.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Orders</a>
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
                    <i class="fas fa-plus icon"></i> Create Product
                </div>

                <div class="main-form">
                <?php
include 'connection/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $price = mysqli_real_escape_string($connection, $_POST['price']);
    // $description = mysqli_real_escape_string($connection, $_POST['description']);

    $sql = "INSERT INTO products (name, quantity, price, description) VALUES ('$name', '$quantity', '$price', '')";

    if ($connection->query($sql) === TRUE) {
        header("Location: success.php?product=$name");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
}

$connection->close();
?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-content">
                        <div><label for="product-name">Product Name</label></div>
                        <div><input style="width: 90%;" type="text" id="product-name" name="name" required></div>

                        <!-- <div><label for="description">Description</label></div>
                        <div><textarea style="width: 90%;" type="text" id="description" name="description" required></textarea></div> -->

                        <div><label for="quantity">Quantity</label></div>
                        <div><input type="number" min="0" class="quant" id="quantity" name="quantity" required></div>

                        <div><label for="price">Price $</label></div>
                        <div><input type="number" min="0" class="quant" id="price" name="price" required></div>

                        <div><button class="btn" type="submit"><i class="fas fa-plus"></i> Create Product</button></div>
                    </form>
                </div>
                
            </div>

            
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.sub-btn').click(function(){
                $(this).next('.sub-menu').slideToggle();
                $(this).find('.dropdown').toggleClass('rotate');
            });
        });
    </script>
</body>
</html>
