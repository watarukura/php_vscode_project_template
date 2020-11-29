<?php

use function OpenApi\scan;

error_reporting(0);
require(__DIR__ . "/../vendor/autoload.php");
$openapi = scan(__DIR__ . "/../src");
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
