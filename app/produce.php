<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

//$producer = new \Lisaldo\Kafka\ProducerDemo();
//$producer = new \Lisaldo\Kafka\ProducerDemoWithCallback();
$producer = new \Lisaldo\Kafka\ProducerDemoKeys();
//$producer = new \Lisaldo\Kafka\ProducerDemoInteractive();

$producer->handle();
