<?php
!defined('KAFKA_SWOOLE_ROOT') && define('KAFKA_SWOOLE_ROOT', dirname(__DIR__));
!defined('KAFKA_SWOOLE_VENDOR') && define('KAFKA_SWOOLE_VENDOR', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor');
!defined('KAFKA_SWOOLE_SRC') && define('KAFKA_SWOOLE_SRC',
    KAFKA_SWOOLE_VENDOR . DIRECTORY_SEPARATOR . 'ccinn/kafka-swoole-core/src');
!defined('KAFKA_SWOOLE_APP') && define('KAFKA_SWOOLE_APP', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app');
!defined('KAFKA_SWOOLE_BIN') && define('KAFKA_SWOOLE_BIN', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bin');
!defined('KAFKA_SWOOLE_CONFIG') && define('KAFKA_SWOOLE_CONFIG', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config');
!defined('KAFKA_SWOOLE_CONFIG_COMMON_FILE') && define('KAFKA_SWOOLE_CONFIG_COMMON_FILE',
    KAFKA_SWOOLE_CONFIG . DIRECTORY_SEPARATOR . 'common.yaml');
