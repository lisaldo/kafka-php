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
        $conf->set(ConsumerConfig::HEARTBEAT_INTERVAL_MS, 2000);
        $conf->set(ConsumerConfig::SESSION_TIMEOUT_MS_CONFIG, 3000);

        $conf->setRebalanceCb(function (KafkaConsumer $kafka, $err, $partitions) {
            /**
             * When at least two consumers are already connected with Kafka and
             * one of them is disconnected, the second one will receive two events
             * - The first one is informing that every partition was revoked and all
             *      topics needs to be unassigned
             * - The second one is informing the new partitions assigned to this
             *      consumer
             */
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    echo "Assign: ";
                    var_dump($partitions);
                    $kafka->assign($partitions);
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    echo "Revoke: ";
                    var_dump($partitions);
                    $kafka->assign(null);
                    break;

                default:
                    throw new \Exception($err);
            }
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