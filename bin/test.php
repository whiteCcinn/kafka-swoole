<?php


/**
 * 1. 接收RPC请求
 */

$server = new \Swoole\Server('127.0.0.1', 9501, SWOOLE_PROCESS, SWOOLE_TCP);

swoole_set_process_name('master');
$server->set([
    'reactor_num' => 1, //reactor thread num
    'worker_num'  => 1,    //worker process num
    'max_request' => 50,
]);

/**
 * 用户进程实现了广播功能，循环接收管道消息，并发给服务器的所有连接
 */
$process = new Swoole\Process(function (\Swoole\Process $process) use ($server) {
    swoole_set_process_name('process');
    swoole_event_add($process->pipe, function () use ($server, $process) {
        $msg = $process->read();
        var_dump($msg);
        foreach ($server->connections as $conn) {
            $server->send($conn, $msg);
        }
    });
    go(function () {
        while (true) {
            var_dump('loop');
            co::sleep(1);
        }
    });
}, false, 1, true);

$server->addProcess($process);

$server->on('ManagerStart', function () {
    swoole_set_process_name("manager");
});

$server->on('WorkerStart', function ($serv, $worker_id) {
    if ($worker_id >= $serv->setting['worker_num']) {
        swoole_set_process_name("task worker");
    } else {
        swoole_set_process_name("event worker");
    }
});

$server->on('receive', function ($serv, $fd, $reactor_id, $data) use ($process) {
    //群发收到的消息
    $process->write($data);
});

$server->start();