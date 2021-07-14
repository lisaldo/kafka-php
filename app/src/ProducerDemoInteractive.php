<?php

namespace Lisaldo\Kafka;

use RdKafka\Conf;
use RdKafka\Producer;

class ProducerDemoInteractive
{
    public function handle()
    {
        // Create Producer properties
        $conf = new Conf();
        $conf->set(ProducerConfig::BOOTSTRAP_SERVERS_CONFIG, 'kafka:9092');

        // Create Producer
        $producer = new Producer($conf);

        // Create a topic
        $topic = $producer->newTopic('fifth_topic');

        while ($var = readline('Informe uma mensagem: ')) {
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, trim($var));
            $producer->flush(10000);
        }
    }
}
