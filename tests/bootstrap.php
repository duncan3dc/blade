<?php

namespace duncan3dc\LaravelTests;

require __DIR__ . "/../vendor/autoload.php";

function getCachePath()
{
    $path = "/tmp/cache/views";

    # Remove any previously cached files
    $files = glob("{$path}/*");
    array_map("unlink", $files);

    return $path;
}
