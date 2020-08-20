<?php

require_once('../src/DevHumor.php');

$dev_humor      = new DevHumor\DevHumor;
$current_page   = $_GET['page'] ?? 1;
$category       = $_GET['category'] ?? 'uncategorized';
$humors         = $dev_humor->getHumorByCategory($current_page, $category)->asArray();

header('Content-Type: application/json');
$result         = array(
    'status'        => 200,
    'prev_page'     => $current_page > 1 ? $current_page - 1 : $current_page,
    'next_page'     => $humors ? $current_page + 1 : 0,
    'total_data'    => count($humors),
    'data'          => $humors
);

if (!$humors) {
    unset($result['next_page']);
}

echo json_encode($result, JSON_PRETTY_PRINT);