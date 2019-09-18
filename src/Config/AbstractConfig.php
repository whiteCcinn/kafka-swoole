<?php
declare(strict_types=1);

namespace Kafka\Config;

use App\Exception\InvalidConfigurationException;
use function preg_match;
/**
 * Class AbstractConfig
 *
 * @package App\Config
 */
abstract class AbstractConfig
{
    /**
     * AbstractConfig constructor.
     */
    public function __construct()
    {
        $this->validate($this);
    }

    /**
     * @param string $prop
     * @param string $value
     *
     * @return bool
     * @throws InvalidConfigurationException
     */
    protected function validateChars(string $prop, string $value): bool
    {
        $legalChars = '/[a-zA-Z0-9\\._\\-]*/';
        switch (preg_match($legalChars, $value)) {
            case true:
                return true;
            default:
                throw new InvalidConfigurationException($prop . " " . $value . " is illegal, contains a character other than ASCII alphanumerics, '.', '_' and '-'");
        }
    }

    /**
     * @param AbstractConfig $config
     *
     * @return mixed
     */
    abstract function validate($config);
}