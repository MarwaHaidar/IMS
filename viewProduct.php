<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/side.css">
    <link rel="stylesheet" href="css/viewProduct.css">
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
                <div class="item">
                    <a class="sub-btn"><i class="fas fa-tag"></i>PRODUCTS
                        <i class="fas fa-angle-right dropdown"></i>
                    </a>
                    <div class="sub-menu">
                <a href="addproduct.php" class="sub-item">Add Product</a>
                <a href="viewProduct.php" class="sub-item">View Products</a>
            </div>
                </div>
                <div class="item">
                    <a class="sub-btn orders-btn"><i class="fas fa-shopping-cart"></i>ORDERS
                        <i class="fas fa-angle-right dropdown"></i>
                    </a>
                    <div class="sub-menu orders-menu">
                        <a href="#" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Order</a>
                        <a href="#" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Orders</a>
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
                    <i class="fas fa-list-ul icon"></i> List of Products
                </div>

                <div class="input-container">
                    <input type="text" placeholder="Search by Product Name..." class="rounded-input">
                    <i class="search-icon fas fa-search"></i>
                </div>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'db_connection.php';

                        $sql = "SELECT `id`, `name`, `quantity`, `price`, `description`, `Created date` FROM `products`";
                        $result = $connection->query($sql);

                        if ($result->num_rows > 0) {

                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"]. "</td>";
                                echo "<td>" . $row["name"]. "</td>";
                                echo "<td>" . $row["quantity"]. "</td>";
                                echo "<td>" . $row["price"]. "</td>";
                                echo "<td>" . $row["description"]. "</td>";
                                echo "<td>" . $row["Created date"]. "</td>";
                                echo "<td><button class='edit-btn'>Edit</button><button class='delete-btn'>Delete</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No products found</td></tr>";
                        }
                        $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.sub-btn').click(function(){
                $(this).next('.sub-menu').slideToggle();
                $(this).find('.dropdown').toggleClass('rotate');
            });

            // Search functionality
            $('.rounded-input').on('input', function() {
                var searchText = $(this).val().toLowerCase();
                $('tbody tr').each(function() {
                    var productName = $(this).find('td:nth-child(2)').text().toLowerCase();
                    if (productName.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
</body>
</html>
