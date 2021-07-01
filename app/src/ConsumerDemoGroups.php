<?php

namespace Lisaldo\Kafka;

use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\TopicPartition;

class ConsumerDemoGroups
{
    public function handle()
    {
        // create consumer config
        $conf = new Conf();
        $conf->set(ConsumerConfig::BOOTSTRAP_SERVERS_CONFIG, 'kafka:9092');
        $conf->set(ConsumerConfig::GROUP_ID_CONFIG, 'my-fourth-application-6');
        $conf->set(ConsumerConfig::AUTO_OFFSET_RESET_CONFIG, 'earliest');
        $conf->set(ConsumerConfig::HEARTBEAT_INTERVAL_MS, 1);

        $conf->setRebalanceCb(function (KafkaConsumer $kafka, $err, $partitions) {
            var_dump($err, $partitions);
        });

        // create consumer
        $consumer = new KafkaConsumer($conf);

        //subscribe consumer to our topic(s)
        $consumer->subscribe(['third_topic']);

        // poll for new data
        while (true) {
            $message = $consumer->consume(5000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    print_r([
                        'Key: ' . $message->key,
                        'Value: ' . $message->payload,
                        'Partition: ' . $message->partition,
                        'Offset: ' . $message->offset,
                    ]);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
            }
        }
    }
}