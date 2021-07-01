<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

//$consumer = new \Lisaldo\Kafka\ConsumerDemo();
$consumer = new \Lisaldo\Kafka\ConsumerDemoGroups();

$consumer->handle();
