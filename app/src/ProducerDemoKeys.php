<?php

namespace Lisaldo\Kafka;

use RdKafka\Conf;
use RdKafka\Message;
use RdKafka\Producer;

class ProducerDemoKeys
{
    public function handle()
    {
        // Create Producer properties
        $conf = $this->generateConf();

        // Create Producer
        $producer = new Producer($conf);

        // Create a topic
        $topic = $producer->newTopic('third_topic');

        for ($i = 0; $i < 10; $i++) {
            $topic->produce(
                RD_KAFKA_PARTITION_UA,
                0,
                'Hello World! (' . $i . ')',
                'id_' . $i,
            );

            // send data
            $producer->flush(5000); // don't do this in production
        }

        $producer->flush(10000);
    }

    /**
     * @return Conf
     */
    public function generateConf(): Conf
    {
        $conf = new Conf();
        $conf->set(ProducerConfig::BOOTSTRAP_SERVERS_CONFIG, 'kafka:9092');
        $conf->setDrMsgCb(function (Producer $kafka, Message $message) {
            if ($message->err) {
                print_r('Error whule producing: ' . $message->errstr());
                return;
            }

            print_r([
                'Received new metada',
                'Topic: ' . $message->topic_name,
                'Partition: ' . $message->partition,
                'Offset: ' . $message->offset,
                'Timestamp: ' . $message->timestamp,
                'Message: ' . $message->payload,
                'Key: ' . $message->key,
            ]);
        });
        return $conf;
    }
}
