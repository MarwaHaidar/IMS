<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/success.css">
    <title>Success</title>
</head>
<body>
    <div class="success-message">
        <h2 class="message">Product '<?php echo $_GET['product']; ?>' was added successfully!</h2>
        <a href="addProduct.php" class="add-more-button">Add More Products</a>
    </div>
</body>
</html>
