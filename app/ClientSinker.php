<?php
declare(strict_types=1);

namespace App;

use Kafka\Support\SingletonTrait;
use Swoole\Process;

/**
 * Class ClientSinker
 *
 * @package App
 */
class ClientSinker
{
    use SingletonTrait;

    /**
     * @var Process $process
     */
    private $process;

    /**
     * @var int $index
     */
    private $index;

    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $process
     *
     * @return ClientSinker
     */
    public function setProcess($process): self
    {
        $this->process = $process;

        return $this;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @param int $index
     *
     * @return ClientSinker
     */
    public function setIndex(int $index): ClientSinker
    {
        $this->index = $index;

        return $this;
    }
}