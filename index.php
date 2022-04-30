<?php
//error_reporting(E_ALL);
//ini_set('display_errors', true);

if (!isset($argc)) {
    die('Run this script from command line only.<br>Usage: <b>' . basename(__FILE__) . ' &lt;URL&gt;</b>');
}
if ($argc !== 2) {
    die("Usage: $argv[0] <URL>");
}

// set to false or comment to hide download errors
const SHOW_ADDITIONAL_INFO = true;

require 'vendor/autoload.php';
require 'autoloader.php';

$url = $argv[1];
//$url = 'https://www.php.net/manual/en/pdo.drivers.php';
//$url = 'https://rus.delfi.lv';

$app = new \App\App($url);
$app
//    ->setSavePath($savePath) // './downloads' by default
    ->addTag('script', 'src')
    ->addTag('a', 'href', false)
    ->addTag('img', 'src')
    ->addTag('link', 'href')
    ->addTag('somewrongtag1', 'attribute1', true)
    ->addTag('somewrongtag2', 'attribute2', false);

$results = $app
    ->load()
    ->run()
    ->download()
    ->getResults();

echo json_encode($results, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
