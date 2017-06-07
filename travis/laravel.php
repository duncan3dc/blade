#!/usr/bin/env php
<?php

if (!getenv("LARAVEL_VERSION")) {
    return;
}

$json = file_get_contents(__DIR__ . "/../composer.json");
$composer = json_decode($json);

foreach ($composer->require as $package => &$version) {
    if (substr($package, 0, 11) === "illuminate/") {
        $version = getenv("LARAVEL_VERSION") . ".*";
    }
}
unset($version);

$json = json_encode($composer);
file_put_contents(__DIR__ . "/../composer.json", $json);
