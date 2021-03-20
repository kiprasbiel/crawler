<?php
require_once __DIR__ . '/vendor/autoload.php';

use Crawler\Crawler;

$class = new Crawler();
echo $class->getResponse();