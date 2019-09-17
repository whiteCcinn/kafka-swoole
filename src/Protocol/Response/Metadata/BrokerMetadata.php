<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response\Metadata;

use Kafka\Protocol\TraitStructure\ToArrayTrait;
use \Kafka\Protocol\Type\Int32;
use \Kafka\Protocol\Type\String16;

/**
 * Class BrokerMetadata
 *
 * @package Kafka\Protocol\Response\Metadata
 */
class BrokerMetadata
{
    use ToArrayTrait;

    /** @var Int32 $nodeId */
   private $nodeId;

   /** @var String16 $host */
   private $host;

   /** @var Int32 $port */
   private $port;

    /**
     * @return Int32
     */
    public function getNodeId(): Int32
    {
        return $this->nodeId;
    }

    /**
     * @param Int32 $nodeId
     *
     * @return BrokerMetadata
     */
    public function setNodeId(Int32 $nodeId): BrokerMetadata
    {
        $this->nodeId = $nodeId;

        return $this;
    }

    /**
     * @return String16
     */
    public function getHost(): String16
    {
        return $this->host;
    }

    /**
     * @param String16 $host
     *
     * @return BrokerMetadata
     */
    public function setHost(String16 $host): BrokerMetadata
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Int32
     */
    public function getPort(): Int32
    {
        return $this->port;
    }

    /**
     * @param Int32 $port
     *
     * @return BrokerMetadata
     */
    public function setPort(Int32 $port): BrokerMetadata
    {
        $this->port = $port;

        return $this;
    }
}