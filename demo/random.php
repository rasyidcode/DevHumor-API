<?php

require_once('../src/DevHumor.php');

$dev_humor      = new DevHumor\DevHumor;
$humor          = $dev_humor->getRandomHumor()->asSingle();

header('Content-Type: application/json');

$result         = array(
    'status'        => 200,
    'data'          => $humor
);

echo json_encode($result, JSON_PRETTY_PRINT);