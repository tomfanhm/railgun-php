<?php

declare(strict_types=1);

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../vendor/autoload.php";

use App\Core\Application;

$app = new Application(dirname(__DIR__));
$app->run();
