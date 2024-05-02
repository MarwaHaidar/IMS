<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/side.css">
    <link rel="stylesheet" href="css/report.css">
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
            <div class="item  active"><a href="reports.php"><i class="fas fa-file"></i>REPORTS</a></div>
            <div class="item">
                <a class="sub-btn"><i class="fas fa-tag"></i>PRODUCTS
                    <i class="fas fa-angle-right dropdown"></i>
                </a>
                <div class="sub-menu">
                    <a href="addProduct.php" class="sub-item">Add Product</a>
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
                    <i class="fas fa-file icon"></i> Reports
                </div>
                <div class="boxes">
        <div class="box">
            <div class="text">Export Products</div>
            <div class="butons">
            <a href="report_csv.php?report=product" class="btn">EXCEL</a>

                <a href="" class="btn">PDF</a>
            </div>
        </div>

        <div class="box">
            <div class="text">Export Orders</div>
            <div class="butons">
                <button class="btn">EXCEL</button>
                <button class="btn">PDF</button>
            </div>
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
