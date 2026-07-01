#!/usr/bin/env php
<?php

$override = $_SERVER["argv"][1] ?? "";
if (!$override) {
    return;
}

$path = __DIR__ . "/../composer.json";

$json = (string) file_get_contents($path);
$composer = json_decode($json);

foreach ($composer->require as $package => &$version) {
    if (substr($package, 0, 11) === "illuminate/") {
        $version = "^{$override}";
    }
}
unset($version);

$json = json_encode($composer);
file_put_contents($path, $json);
