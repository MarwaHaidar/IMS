<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = filter_input(INPUT_GET, 'report', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$mapping_filenames = [
    'product' => 'Product Report',
    'orders' => 'Orders Report',
];

if (array_key_exists($type, $mapping_filenames)) {
    $file_name = $mapping_filenames[$type] . '.csv';

    header("Content-Disposition: attachment; filename=\"$file_name\"");
    header("Content-Type: text/csv; charset=UTF-8");

    // Include the database connection
    include('connection/db.php');

    if ($type === 'product') {
        // Open output stream
        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel
        fputs($output, "\xEF\xBB\xBF");

        $query = "SELECT * FROM products";
        $result = mysqli_query($connection, $query);

        if ($result) {
            $columnsPrinted = false;
            while ($row = mysqli_fetch_assoc($result)) {
                if (!$columnsPrinted) {
                    // Output column headings
                    fputcsv($output, array_keys($row));
                    $columnsPrinted = true;
                }
                // Output row data
                fputcsv($output, $row);
            }
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        fclose($output);
    } elseif ($type === 'orders') {
        // Open output stream
        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel
        fputs($output, "\xEF\xBB\xBF");

        $query = "SELECT * FROM orders"; 
        $result = mysqli_query($connection, $query);

        if ($result) {
            $columnsPrinted = false;
            while ($row = mysqli_fetch_assoc($result)) {
                if (!$columnsPrinted) {
                    // Output column headings
                    fputcsv($output, array_keys($row));
                    $columnsPrinted = true;
                }
                // Output row data
                fputcsv($output, $row);
            }
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        fclose($output);
    }
} else {
    echo "Invalid report type.";
}
?>
