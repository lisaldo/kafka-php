<?php


namespace Lisaldo\Kafka;


interface ProducerConfig
{
    public const BOOTSTRAP_SERVERS_CONFIG = 'bootstrap.servers';
    public const KEY_SERIALIZER_CLASS_CONFIG = 'key.serializer';
    public const VALUE_SERIALIZER_CLASS_CONFIG = 'value.serializer';
}