<?php
declare(strict_types=1);

namespace Kafka\Protocol;

class CommonRequest extends AbstractRequest
{
    /**
     * @var callable $continueCallBack
     */
    private $continueCallBack;

    /**
     * @return callable
     */
    public function getContinueCallBack(): callable
    {
        return $this->continueCallBack;
    }

    /**
     * @param callable $continueCallBack
     *
     * @return CommonRequest
     */
    public function setContinueCallBack(callable $continueCallBack): CommonRequest
    {
        $this->continueCallBack = $continueCallBack;

        return $this;
    }

    /**
     * @param $instance
     * @param $className
     * @param $wrapperProtocol
     * @param $propertyName
     *
     * @return bool
     */
    protected function continueCallBack($instance, $className, $wrapperProtocol, $propertyName): bool
    {
        if (is_callable($this->continueCallBack)) {
            return $this->getContinueCallBack()($instance, $className, $wrapperProtocol, $propertyName);
        } else {
            return false;
        }
    }
}
