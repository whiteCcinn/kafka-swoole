<?php

namespace Kafka\Socket;


use Kafka\Exception\Socket\NormalSocketException;

class NormalSocket
{
    private const READ_MAX_LENGTH = 5242880;

    private const MAX_WRITE_BUFFER = 2048;

    /**
     * @var resource $normalSocket
     */
    private $normalSocket;

    /**
     * Send timeout in seconds.
     *
     * @var int $sendTimeoutSec
     */
    private $sendTimeoutSec = 0;

    /**
     * Send timeout in microseconds.
     *
     * @var int $sendTimeoutUsec
     */
    private $sendTimeoutUsec = 100000;

    /**
     * Recv timeout in seconds
     *
     * @var int $recvTimeoutSec
     */
    private $recvTimeoutSec = 0;

    /**
     * Recv timeout in microseconds
     *
     * @var int $recvTimeoutUsec
     */
    private $recvTimeoutUsec = 750000;

    /**
     * @var string $host
     */
    private $host;

    /**
     * @var int
     */
    private $port = -1;

    /**
     * @var int
     */
    private $connectTimeOut = -1;

    /**
     * @var int
     */
    protected $maxWriteAttempts = 3;

    /**
     * @return resource
     */
    public function getNormalSocket()
    {
        return $this->normalSocket;
    }

    /**
     * @param resource $normalSocket
     *
     * @return NormalSocket
     */
    public function setNormalSocket($normalSocket): NormalSocket
    {
        $this->normalSocket = $normalSocket;

        return $this;
    }

    /**
     * @return int
     */
    public function getSendTimeoutSec(): int
    {
        return $this->sendTimeoutSec;
    }

    /**
     * @param int $sendTimeoutSec
     *
     * @return NormalSocket
     */
    public function setSendTimeoutSec(int $sendTimeoutSec): NormalSocket
    {
        $this->sendTimeoutSec = $sendTimeoutSec;

        return $this;
    }

    /**
     * @return int
     */
    public function getSendTimeoutUsec(): int
    {
        return $this->sendTimeoutUsec;
    }

    /**
     * @param int $sendTimeoutUsec
     *
     * @return NormalSocket
     */
    public function setSendTimeoutUsec(int $sendTimeoutUsec): NormalSocket
    {
        $this->sendTimeoutUsec = $sendTimeoutUsec;

        return $this;
    }

    /**
     * @return int
     */
    public function getRecvTimeoutSec(): int
    {
        return $this->recvTimeoutSec;
    }

    /**
     * @param int $recvTimeoutSec
     *
     * @return NormalSocket
     */
    public function setRecvTimeoutSec(int $recvTimeoutSec): NormalSocket
    {
        $this->recvTimeoutSec = $recvTimeoutSec;

        return $this;
    }

    /**
     * @return int
     */
    public function getRecvTimeoutUsec(): int
    {
        return $this->recvTimeoutUsec;
    }

    /**
     * @param int $recvTimeoutUsec
     *
     * @return NormalSocket
     */
    public function setRecvTimeoutUsec(int $recvTimeoutUsec): NormalSocket
    {
        $this->recvTimeoutUsec = $recvTimeoutUsec;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return NormalSocket
     */
    public function setHost(string $host): NormalSocket
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return NormalSocket
     */
    public function setPort(int $port): NormalSocket
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxWriteAttempts(): int
    {
        return $this->maxWriteAttempts;
    }

    /**
     * @param int $maxWriteAttempts
     *
     * @return NormalSocket
     */
    public function setMaxWriteAttempts(int $maxWriteAttempts): NormalSocket
    {
        $this->maxWriteAttempts = $maxWriteAttempts;

        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeOut(): int
    {
        return $this->connectTimeOut;
    }

    /**
     * @param int $connectTimeOut
     *
     * @return NormalSocket
     */
    public function setConnectTimeOut(int $connectTimeOut): NormalSocket
    {
        $this->connectTimeOut = $connectTimeOut;

        return $this;
    }

    /**
     * @param string $host
     * @param int    $port
     * @param float  $timeout
     *
     * @throws \Kafka\Exception\Socket\NormalSocketConnectException
     */
    public function connect(string $host, int $port = 0, float $timeout = -1)
    {
        $this->setHost($host)->setPort($port)->setConnectTimeOut($timeout);
        $this->createSocket();
        stream_set_blocking($this->getnormalSocket(), false);
        stream_set_read_buffer($this->getnormalSocket(), 0);
    }

    /**
     * @throws \Kafka\Exception\Socket\NormalSocketConnectException
     */
    public function createSocket()
    {
        $remoteSocket = sprintf('tcp://%s:%s', $this->host, $this->port);
        $context = stream_context_create([]);
        $this->setNormalSocket(
            stream_socket_client(
                $remoteSocket,
                $errno,
                $errstr,
                $this->sendTimeoutSec + ($this->sendTimeoutUsec / 1000000),
                STREAM_CLIENT_CONNECT,
                $context
            )
        );

        if (!is_resource($this->normalSocket)) {
            throw NormalSocketException::connectFail(
                $this->host, $this->port, $errstr, $errno
            );
        }
    }

    /**
     * @param array $sockets
     * @param int   $timeoutSec
     * @param int   $timeoutUsec
     * @param bool  $isRead
     *
     * @return int
     */
    private function select(array $sockets, int $timeoutSec, int $timeoutUsec, bool $isRead = true)
    {
        $null = null;

        if ($isRead) {
            return @stream_select($sockets, $null, $null, $timeoutSec, $timeoutUsec);
        }

        return @stream_select($null, $sockets, $null, $timeoutSec, $timeoutUsec);
    }

    /**
     * @return array
     */
    private function getMetaData(): array
    {
        return stream_get_meta_data($this->normalSocket);
    }

    /**
     * @param int $length
     *
     * @return string
     * @throws NormalSocketException
     */
    public function recv(int $length): string
    {
        if ($length > self::READ_MAX_LENGTH) {
            throw NormalSocketException::invalidLength($length, self::READ_MAX_LENGTH);
        }

        $readable = $this->select([$this->normalSocket], $this->recvTimeoutSec, $this->recvTimeoutUsec);

        if ($readable === false) {
            $this->close();
            throw NormalSocketException::notReadable($length);
        }

        if ($readable === 0) { // select timeout
            $res = $this->getMetaData();
            $this->close();

            if (!empty($res['timed_out'])) {
                throw NormalSocketException::timedOut($length);
            }

            throw NormalSocketException::notReadable($length);
        }

        $remainingBytes = $length;
        $data = $chunk = '';

        while ($remainingBytes > 0) {
            $chunk = fread($this->normalSocket, $remainingBytes);

            if ($chunk === false || strlen($chunk) === 0) {
                // Zero bytes because of EOF?
                if (feof($this->normalSocket)) {
                    $this->close();
                    throw NormalSocketException::unexpectedEOF($length);
                }
                // Otherwise wait for bytes
                $readable = $this->select([$this->normalSocket], $this->recvTimeoutSec, $this->recvTimeoutUsec);
                if ($readable !== 1) {
                    throw NormalSocketException::timedOutWithRemainingBytes($length, $remainingBytes);
                }

                continue; // attempt another read
            }

            $data .= $chunk;
            $remainingBytes -= strlen($chunk);
        }

        return $data;
    }

    /**
     * @param string $buffer
     *
     * @return int
     * @throws NormalSocketException
     */
    public function send(string $buffer): int
    {
        // fwrite to a socket may be partial, so loop until we
        // are done with the entire buffer
        $failedAttempts = 0;
        $bytesWritten = 0;

        $bytesToWrite = strlen($buffer);

        while ($bytesWritten < $bytesToWrite) {
            // wait for stream to become available for writing
            $writable = $this->select([$this->normalSocket], $this->sendTimeoutSec, $this->sendTimeoutUsec, false);

            if ($writable === false) {
                throw new NormalSocketException('Could not write ' . $bytesToWrite . ' bytes to stream');
            }

            if ($writable === 0) {
                $res = $this->getMetaData();
                if (!empty($res['timed_out'])) {
                    throw new \Exception('Timed out writing ' . $bytesToWrite . ' bytes to stream after writing ' . $bytesWritten . ' bytes');
                }

                throw new NormalSocketException('Could not write ' . $bytesToWrite . ' bytes to stream');
            }

            if ($bytesToWrite - $bytesWritten > self::MAX_WRITE_BUFFER) {
                // write max buffer size
                $wrote = fwrite($this->normalSocket, substr($buffer, $bytesWritten, self::MAX_WRITE_BUFFER));
            } else {
                // write remaining buffer bytes to stream
                $wrote = fwrite($this->normalSocket, substr($buffer, $bytesWritten));
            }

            if ($wrote === -1 || $wrote === false) {
                throw new NormalSocketException('Could not write ' . strlen($buffer) . ' bytes to stream, completed writing only ' . $bytesWritten . ' bytes');
            }

            if ($wrote === 0) {
                // Increment the number of times we have failed
                $failedAttempts++;

                if ($failedAttempts > $this->maxWriteAttempts) {
                    throw new NormalSocketException('After ' . $failedAttempts . ' attempts could not write ' . strlen($buffer) . ' bytes to stream, completed writing only ' . $bytesWritten . ' bytes');
                }
            } else {
                // If we wrote something, reset our failed attempt counter
                $failedAttempts = 0;
            }

            $bytesWritten += $wrote;
        }

        return $bytesWritten;
    }

    public function getRawSocket()
    {
        return $this->normalSocket;
    }

    public function close()
    {
        if (is_resource($this->normalSocket)) {
            fclose($this->normalSocket);
        }
    }
}