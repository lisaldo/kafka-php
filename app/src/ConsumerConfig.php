<?php

namespace Lisaldo\Kafka;

interface ConsumerConfig
{
    public const BOOTSTRAP_SERVERS_CONFIG = 'bootstrap.servers';
    public const KEY_DESERIALIZER_CLASS_CONFIG = 'key.deserializer';
    public const VALUE_DESERIALIZER_CLASS_CONFIG = 'value.deserializer';
    public const GROUP_ID_CONFIG = 'group.id';
    public const AUTO_OFFSET_RESET_CONFIG = 'auto.offset.reset';
    public const HEARTBEAT_INTERVAL_MS = 'heartbeat.interval.ms'; // time to alert Kafka that consumer is still connected
    public const SESSION_TIMEOUT_MS_CONFIG = 'session.timeout.ms'; // timeout to Kafka consider consumer disconnected
}