<?php
$type = $_GET['report'];

$mapping_filenames = [
    'product' => 'Product Report',
    'orders' => 'Orders Report',
];

$file_name = $mapping_filenames[$type] . '.xls';

header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: application/vnd.ms-excel");
?>
