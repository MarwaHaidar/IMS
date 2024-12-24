<?php
include 'connection/db.php';

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product";
    }
    
    $stmt->close();
    $connection->close();
    exit;
}

// Handle product editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product_id'])) {
    $product_id = $_POST['edit_product_id'];
    $product_name = $_POST['edit_product_name'];
    $product_quantity = $_POST['edit_product_quantity'];
    $product_price = $_POST['edit_product_price'];
    $product_description = $_POST['edit_product_description'];

    $sql = "UPDATE products SET name=?, quantity=?, price=?, description=? WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("siisi", $product_name, $product_quantity, $product_price, $product_description, $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product";
    }
    
    $stmt->close();
    exit;
}
?>

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
    <style>
        .fixed-input-container {
            padding: 20px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            display: none;
        }

        .fixed-input-container input {
            border: 1px solid rgb(160, 159, 159);
            border-radius: 5px;
            padding: 5px;
            margin: 10px;
        }

        .save-btn{
        padding: 10px;
        background-color: rgb(237, 236, 236);
        color: black;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        margin-right: 15px;
        padding-left: 15px;
       padding-right: 15px; 
        }
        
.save-btn:hover{
    background-color: gray;
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
                <div class="item"><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>DASHBOARD</a></div>
                <div class="item"><a href="reports.php"><i class="fas fa-file"></i>REPORTS</a></div>
                <div class="item  active">
                    <a class="sub-btn active"><i class="fas fa-tag"></i>PRODUCTS
                        <i class="fas fa-angle-right dropdown"></i>
                    </a>
                    <div class="sub-menu">
                        <a href="addProduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>Add Product</a>
                        <a href="viewProduct.php" class="sub-item"><i class="fas fa-circle circle-icon"></i>View Products</a>
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
                    <i class="fas fa-list-ul icon"></i> List of Products
                </div>
                
                <div class="input-container">
                    <input type="text" placeholder="Search by Product Name..." class="rounded-input">
                    <i class="search-icon fas fa-search"></i>
                </div>
                <div class="fixed-input-container">
                    <div class="edit-container">
                        <input type="hidden" class="edit-product-id" value="">
                        <input type="text" class="edit-product-name" placeholder="Product Name">
                        <input type="number" class="edit-product-quantity" placeholder="Stock">
                        <input type="number" class="edit-product-price" placeholder="Price">
                        <input type="text" class="edit-product-description" placeholder="Description">
                        <button class="save-btn">Save</button>
                    </div>
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
                                echo "<td>
                                        <button class='edit-btn' data-product-id='" . $row["id"] . "'>Edit</button>
                                        <button class='delete-btn' data-product-id='" . $row["id"] . "'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No products found</td></tr>";
                        }
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

    // Edit button click event
    $('.edit-btn').click(function() {
        var productId = $(this).data('product-id');
        var row = $(this).closest('tr');
        var productName = row.find('td:nth-child(2)').text();
        var productQuantity = row.find('td:nth-child(3)').text();
        var productPrice = row.find('td:nth-child(4)').text();
        var productDescription = row.find('td:nth-child(5)').text();

        // Populate input fields with row data
        $('.edit-product-id').val(productId);
        $('.edit-product-name').val(productName);
        $('.edit-product-quantity').val(productQuantity);
        $('.edit-product-price').val(productPrice);
        $('.edit-product-description').val(productDescription);
        $('.fixed-input-container').slideDown();
    });

    // Save button click event
    $('.save-btn').click(function() {
        var productId = $('.edit-product-id').val();
        var productName = $('.edit-product-name').val();
        var productQuantity = $('.edit-product-quantity').val();
        var productPrice = $('.edit-product-price').val();
        var productDescription = $('.edit-product-description').val();
        $('.fixed-input-container').slideUp();

        // Send edited data to server using AJAX
        $.post(window.location.href, {
            edit_product_id: productId,
            edit_product_name: productName,
            edit_product_quantity: productQuantity,
            edit_product_price: productPrice,
            edit_product_description: productDescription
        }, function(response) {
            console.log(response);
            location.reload();
        });
    });

    // Delete button click event
    $('.delete-btn').click(function() {
        var productId = $(this).data('product-id');
        var rowToDelete = $(this).closest('tr');
        
        var confirmation = confirm("Are you sure you want to delete this product?");
        if (confirmation) {
            $.post(window.location.href, { product_id: productId }, function(response) {
                console.log(response);
                rowToDelete.remove();
            });
        }
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

</body>
</html>
