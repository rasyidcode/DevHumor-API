<?php

require_once('../src/DevHumor.php');

$dev_humor      = new DevHumor\DevHumor;
$current_page   = $_GET['page'] ?? 1;
$humors         = $dev_humor->getMainPageHumors($current_page)->asArray();

header('Content-Type: application/json');
echo json_encode(array(
    'status'        => 200,
    'prev_page'     => $current_page - 1,
    'next_page'     => $current_page + 1,
    'total_data'    => count($humors),
    'data'          => $humors
), JSON_PRETTY_PRINT);