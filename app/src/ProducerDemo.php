<?php

namespace Lisaldo\Kafka;

use RdKafka\Conf;
use RdKafka\Producer;

class ProducerDemo
{
    public function handle()
    {
        // Create Producer properties
        $conf = new Conf();
        $conf->set(ProducerConfig::BOOTSTRAP_SERVERS_CONFIG, 'kafka:9092');

        // Create Producer
        $producer = new Producer($conf);

        // Create a topic
        $topic = $producer->newTopic('first_topic');
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, 'Hello World!');

        // send data
        for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
            $result = $producer->flush(10000);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                break;
            }
        }
    }
}
