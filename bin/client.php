<?php
$client = new swoole_client(SWOOLE_TCP);
if (!$client->connect('127.0.0.1', 9501, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}

$command = [
    'cmd' => 'kafka_lag'
];

$client->send("hello world\n");
echo $client->recv();
$client->close();