<?php

namespace duncan3dc\LaravelTests;

use function glob;
use function unlink;

final class Utils
{
    public static function getCachePath(): string
    {
        $path = "/tmp/cache/views";

        # Remove any previously cached files
        $files = glob("{$path}/*");
        foreach ($files as $filename) {
            unlink($filename);
        }

        return $path;
    }
}
