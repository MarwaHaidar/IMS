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
    $file_name = $mapping_filenames[$type] . '.pdf';

    // Include the TCPDF library
    require_once('tcpdf/tcpdf.php');

    // Create new PDF document
    // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // // Set document information
    // $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle($mapping_filenames[$type]);
    $pdf->SetSubject($mapping_filenames[$type]);

    // Add a page
    $pdf->AddPage();

    // Include the database connection
    include('connection/db.php');

    // Content
    $content = '<h1>' . $mapping_filenames[$type] . '</h1>';

    if ($type === 'product') {
        $query = "SELECT * FROM products";
        $result = mysqli_query($connection, $query);

        if ($result) {
            $content .= '<table border="1" cellspacing="3" cellpadding="4">';
            $first_row = true;
            while ($row = mysqli_fetch_assoc($result)) {
                if ($first_row) {
                    $content .= '<tr>';
                    foreach ($row as $key => $value) {
                        $content .= '<th>' . htmlspecialchars($key) . '</th>';
                    }
                    $content .= '</tr>';
                    $first_row = false;
                }
                $content .= '<tr>';
                foreach ($row as $value) {
                    $content .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $content .= '</tr>';
            }
            $content .= '</table>';
        } else {
            $content = "Error: " . mysqli_error($connection);
        }
    } elseif ($type === 'orders') {
        $query = "SELECT * FROM orders"; // Adjust query according to your database structure
        $result = mysqli_query($connection, $query);

        if ($result) {
            $content .= '<table border="1" cellspacing="3" cellpadding="4">';
            $first_row = true;
            while ($row = mysqli_fetch_assoc($result)) {
                if ($first_row) {
                    $content .= '<tr>';
                    foreach ($row as $key => $value) {
                        $content .= '<th>' . htmlspecialchars($key) . '</th>';
                    }
                    $content .= '</tr>';
                    $first_row = false;
                }
                $content .= '<tr>';
                foreach ($row as $value) {
                    $content .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $content .= '</tr>';
            }
            $content .= '</table>';
        } else {
            $content = "Error: " . mysqli_error($connection);
        }
    }

    // Print text using writeHTML()
    $pdf->writeHTML($content, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output($file_name, 'D');

    // Stop script execution
    exit;
} else {
    echo "Invalid report type.";
}
?>
