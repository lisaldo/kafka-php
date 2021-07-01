<?php

namespace Lisaldo\Kafka;

interface ConsumerConfig
{
    public const BOOTSTRAP_SERVERS_CONFIG = 'bootstrap.servers';
    public const KEY_DESERIALIZER_CLASS_CONFIG = 'key.deserializer';
    public const VALUE_DESERIALIZER_CLASS_CONFIG = 'value.deserializer';
    public const GROUP_ID_CONFIG = 'group.id';
    public const AUTO_OFFSET_RESET_CONFIG = 'auto.offset.reset';
    public const SESSION_TIMEOUT_MS_CONFIG = 'session.timeout.ms';
    public const HEARTBEAT_INTERVAL_MS = 'heartbeat.interval.ms';
}