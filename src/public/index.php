<?php declare(strict_types = 1);

include("../../vendor/autoload.php");

use Searchmetrics\SeniorTest\Controllers\UrlController;


$url = $_GET["url"];

$urlController = new UrlController();
$data = $urlController->getCodeByUrl($url);

echo $data;