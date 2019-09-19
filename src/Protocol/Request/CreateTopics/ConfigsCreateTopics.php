<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\CreateTopics;

use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\String16;

class ConfigsCreateTopics
{
    /**
     * The configuration name.
     *
     * @var String16 $name
     */
    private $name;

    /**
     * The configuration value.
     *
     * @var String16 $value
     */
    private $value;

    /**
     * @return String16
     */
    public function getName(): String16
    {
        return $this->name;
    }

    /**
     * @param String16 $name
     *
     * @return ConfigsCreateTopics
     */
    public function setName(String16 $name): ConfigsCreateTopics
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return String16
     */
    public function getValue(): String16
    {
        return $this->value;
    }

    /**
     * @param String16 $value
     *
     * @return ConfigsCreateTopics
     */
    public function setValue(String16 $value): ConfigsCreateTopics
    {
        $this->value = $value;

        return $this;
    }
}