<?php

use duncan3dc\Helpers\Env;

require __DIR__ . "/../vendor/autoload.php";

Env::usePath(__DIR__);

$files = glob(Env::path("cache/views/*"));
foreach ($files as $filename) {
    unlink($filename);
}
