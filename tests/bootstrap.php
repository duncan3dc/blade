<?php

namespace duncan3dc\LaravelTests;

require __DIR__ . "/../vendor/autoload.php";

const CACHE_PATH = "/tmp/cache/views";

$files = glob(CACHE_PATH . "/*");
array_map("unlink", $files);
