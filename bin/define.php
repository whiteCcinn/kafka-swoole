<?php
!defined('KAFKA_SWOOLE_ROOT') && define('KAFKA_SWOOLE_ROOT', dirname(__DIR__));
!defined('KAFKA_SWOOLE_SRC') && define('KAFKA_SWOOLE_SRC', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
!defined('KAFKA_SWOOLE_APP') && define('KAFKA_SWOOLE_APP', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app');
!defined('KAFKA_SWOOLE_BIN') && define('KAFKA_SWOOLE_BIN', dirname(__DIR__) . DIRECTORY_SEPARATOR, 'bin');
!defined('KAFKA_SWOOLE_CONFIG') && define('KAFKA_SWOOLE_CONFIG', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config');
!defined('KAFKA_SWOOLE_CONFIG_HIGH_LEVEL_FILE') && define('KAFKA_SWOOLE_CONFIG_HIGH_LEVEL_FILE',
    KAFKA_SWOOLE_CONFIG . DIRECTORY_SEPARATOR . 'high_level.yaml');
!defined('KAFKA_SWOOLE_CONFIG_HIGH_LOW_FILE') && define('KAFKA_SWOOLE_CONFIG_LOW_LEVEL_FILE',
    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'low_level.yaml');
!defined('KAFKA_SWOOLE_CONFIG_HIGH_PRODUCER_FILE') && define('KAFKA_SWOOLE_CONFIG_HIGH_PRODUCER_FILE',
    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'producer.yaml');