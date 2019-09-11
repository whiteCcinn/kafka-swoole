<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Exception\ProtocolTypeException;
use Kafka\Protocol\Request\Common\RequestHeader;

use function call_user_func;
use Kafka\Support\Str;

abstract class AbstractRequest extends AbstractRequestOrResponse
{
    /**
     * @var RequestHeader $requestHeader
     */
    protected $requestHeader;

    /**
     * @return RequestHeader
     */
    public function getRequestHeader(): RequestHeader
    {
        return $this->requestHeader;
    }

    /**
     * @param RequestHeader $requestHeader
     *
     * @return AbstractRequest
     */
    public function setRequestHeader(RequestHeader $requestHeader): AbstractRequest
    {
        $this->requestHeader = $requestHeader;

        return $this;
    }

    /**
     * @param null   $fullClassName
     * @param string $protocol
     *
     * @return string
     * @throws ProtocolTypeException
     * @throws \ReflectionException
     */
    public function pack($fullClassName = null, $instance = null, $protocol = '')
    {
        $fullClassName = $fullClassName ?? static::class;
        $instance = $instance ?? $this;
        $refClass = new \ReflectionClass($fullClassName);

        $classNamespace = $refClass->getNamespaceName();
        $typeNamespace = __NAMESPACE__ . '\Type\\';

        $shortClassName = Str::after($fullClassName, "{$classNamespace}\\");

        $refProperties = $refClass->getProperties();
        foreach ($refProperties as $refProperty) {
            $propertyComment = $refProperty->getDocComment();
            $propertyName = $refProperty->getName();
            if (preg_match('/.*@var\s+(?P<protocolType>\w+(\[\])?)\s+.*/', $propertyComment, $matches)) {
                $isArray = false;
                if (preg_match('/^(?P<protocolType>.*)\[\]$/', $matches['protocolType'], $matches2)) {
                    $className = $this->correctionClassName($shortClassName, $classNamespace, $typeNamespace,
                        $matches2['protocolType']);
                    $isArray = true;
                } else {
                    $className = $this->correctionClassName($shortClassName, $classNamespace, $typeNamespace,
                        $matches['protocolType']);
                }

                var_dump($className);
                if ($isArray) {
                    $protocolObjectArray = $this->getPropertyValue($instance, $propertyName);
                    $arrayCount = count($protocolObjectArray);
                    $protocol .= pack(ProtocolTypeEnum::getTextByCode(ProtocolTypeEnum::B32), $arrayCount);
                    foreach ($protocolObjectArray as $protocolObject) {
                        $protocol = $this->pack($className, $protocolObject, $protocol);
                    }
                } else {
                    $wrapperProtocol = call_user_func([$className, 'getWrapperProtocol']);
                    echo "wrapperProtocol : {$wrapperProtocol}, value : " . var_export($this->getTypePropertyValue($instance,
                            $propertyName),
                            true) . PHP_EOL;
                    $protocol .= pack($wrapperProtocol, (string)$this->getTypePropertyValue($instance, $propertyName));
                }
            } else {
                throw new ProtocolTypeException("protocolType undefined , comment: " . $propertyComment);
            }
        }

        return $protocol;
    }

    /**
     * @param string $shortClassName
     * @param string $classNamespace
     * @param string $typeNamespace
     * @param string $protocolType
     *
     * @return string
     * @throws ProtocolTypeException
     */
    private function correctionClassName(
        string $shortClassName,
        string $classNamespace,
        string $typeNamespace,
        string $protocolType
    ): string
    {
        $className = "{$typeNamespace}{$protocolType}";
        if (!class_exists($className, false)) {
            if (Str::endsWith($shortClassName, 'Request')) {
                $secondNamespace = Str::before($shortClassName, 'Request');
                $className = "{$classNamespace}\\{$secondNamespace}\\{$protocolType}";
            } elseif ($protocolType == 'RequestHeader') {
                $className = Str::before($classNamespace, 'Request') . "Common\\{$protocolType}";
            } else {
                $className = "{$classNamespace}\\{$protocolType}";
            }
        }
        var_dump($className);

        if (!class_exists($className, false)) {
            throw new ProtocolTypeException('There are no protocol mines');
        }

        return $className;
    }


    /**
     * @param mixed  $instance
     * @param string $propertyName
     *
     * @return mixed
     */
    private function getTypePropertyValue($instance, string $propertyName)
    {
        $getMethod = Str::camel('get_' . $propertyName);
        $getValueMethod = 'getValue';
        $value = $instance->{$getMethod}()->{$getValueMethod}();

        return $value;
    }

    /**
     * @param mixed  $instance
     * @param string $propertyName
     *
     * @return mixed
     */
    private function getPropertyValue($instance, string $propertyName)
    {
        $getMethod = Str::camel('get_' . $propertyName);
        $value = $instance->{$getMethod}();

        return $value;
    }
}
