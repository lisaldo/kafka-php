<?php

namespace Lisaldo\Kafka;

use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\TopicPartition;

class ConsumerDemoAssignSeek
{
    public function handle()
    {
        // create consumer config
        $conf = $this->consumerConfig();

        // create consumer
        $consumer = new KafkaConsumer($conf);

        // assign and seek are mostly used to replay data or fetch a specific message

        // assign
        $partitionToReadFrom = new TopicPartition('third_topic', 1);

        // seek
        $partitionToReadFrom->setOffset(12);

        $consumer->assign([$partitionToReadFrom]);

        $numberOfMessagesToRead = 5;
        for ($i = 0; $i < $numberOfMessagesToRead; $i++) {
            $message = $consumer->consume(5000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    print_r([
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

    /**
     * @return Conf
     */
    public function consumerConfig(): Conf
    {
        $conf = new Conf();
        $conf->set(ConsumerConfig::BOOTSTRAP_SERVERS_CONFIG, 'kafka:9092');
        $conf->set(ConsumerConfig::AUTO_OFFSET_RESET_CONFIG, 'beginning');
        $conf->set(ConsumerConfig::GROUP_ID_CONFIG, 'required2');
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
        return $conf;
    }
}