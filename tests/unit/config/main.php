<?php

$config = [
    'id' => 'testapp',
    'basePath' => realpath(__DIR__ . '/..'),
];


if (is_file(__DIR__ . '/config.local.php')) {
    include(__DIR__ . '/config.local.php');
}

return $config;
