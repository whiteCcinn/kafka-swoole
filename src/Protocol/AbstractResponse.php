<?php
declare(strict_types=1);

namespace Kafka\Protocol;

use Kafka\Enum\ProtocolTypeEnum;
use Kafka\Exception\ProtocolTypeException;
use Kafka\Protocol\Request\Common\RequestHeader;
use Kafka\Protocol\Response\Common\ResponseHeader;
use Kafka\Protocol\Type\Arrays32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Support\Str;
use \ReflectionClass;
use \ReflectionProperty;

abstract class AbstractResponse extends AbstractRequestOrResponse
{
    /**
     * @var ResponseHeader $requestHeader
     */
    protected $responseHeader;

    /**
     * @return ResponseHeader
     */
    public function getResponseHeader(): ResponseHeader
    {
        return $this->responseHeader;
    }

    /**
     * @param ResponseHeader $responseHeader
     *
     * @return AbstractResponse
     */
    public function setResponseHeader(ResponseHeader $responseHeader): AbstractResponse
    {
        $this->responseHeader = $responseHeader;

        return $this;
    }

    /**
     * @param string $protocol
     *
     * @throws ProtocolTypeException
     * @throws \ReflectionException
     */
    public function unpack(string $protocol)
    {
        $decodeProtocol = $protocol;
        $this->unpackProtocol(null, null, $decodeProtocol);
    }

    /**
     * @param null   $fullClassName
     * @param null   $instance
     * @param string $protocol
     *
     * @throws ProtocolTypeException
     * @throws \ReflectionException
     */
    public function unpackProtocol($fullClassName = null, $instance = null, &$protocol = '')
    {
        $fullClassName = $fullClassName ?? static::class;
        $instance = $instance ?? $this;
        $refClass = new ReflectionClass($fullClassName);

        $classNamespace = $refClass->getNamespaceName();
        $typeNamespace = __NAMESPACE__ . '\Type\\';

        $shortClassName = Str::after($fullClassName, "{$classNamespace}\\");

        $refProperties = $this->getProperties($refClass);
        foreach ($refProperties as $refProperty) {
            $propertyComment = $refProperty->getDocComment();
            $propertyName = $refProperty->getName();
            echo "开始解析 {$propertyName}, protocol size :" . strlen($protocol) . PHP_EOL;
            if (preg_match('/.*@var\s+(?P<protocolType>\w+)(?P<isArray>\[\])?\s+.*/', $propertyComment,
                $matches)) {
                $isArray = isset($matches['isArray']) ? true : false;
                $protocolType = $matches['protocolType'];
                $className = $this->correctionClassName($shortClassName, $classNamespace, $typeNamespace,
                    $protocolType);

                $classNameRef = new ReflectionClass($className);

                if ($isArray) {
                    $value = [];
                    $wrapperProtocol = ProtocolTypeEnum::getTextByCode(ProtocolTypeEnum::B32);
                    $bytes = ProtocolTypeEnum::B32;
                    $buffer = substr($protocol, 0, $bytes);
                    $protocol = substr($protocol, $bytes);
                    $arrayCount = unpack($wrapperProtocol, $buffer);
                    $arrayCount = is_array($arrayCount) ? array_shift($arrayCount) : $arrayCount;
                    echo "{$propertyName} count : " . $arrayCount . PHP_EOL;
                    while ($arrayCount > 0) {
                        if (!Str::startsWith($className, $typeNamespace)) {
                            $value[] = $classNameInstance = $classNameRef->newInstanceWithoutConstructor();
                            $this->unpackProtocol($className, $classNameInstance, $protocol);
                        } else {
                            $wrapperProtocol = call_user_func([$className, 'getWrapperProtocol']);
                            $bytes = ProtocolTypeEnum::getCodeByText($wrapperProtocol);
                            $buffer = substr($protocol, 0, $bytes);
                            $protocol = substr($protocol, $bytes);
                            if ($className === Int64::class) {
                                $set = unpack($wrapperProtocol, $buffer);
                                $data = ($set[1] & 0xFFFFFFFF) << 32 | ($set[2] & 0xFFFFFFFF);
                            } else {
                                $data = unpack($wrapperProtocol, $buffer);
                            }
                            $data = is_array($data) ? array_shift($data) : $data;
                            if ($className === String16::class) {
                                $length = $data;
                                $data = substr($protocol, 0, $length);
                                $protocol = substr($protocol, $data);
                            }
                            $valueInstance = call_user_func([$className, 'value'], $data);
                            $value[] = $valueInstance;
                        }
                        $arrayCount--;
                    }
//                    echo "[-] {$className}\twrapperProtocol : {$wrapperProtocol}, name: {$propertyName}, value : " . var_export($value,
//                            true) . PHP_EOL;
                    $this->setTypePropertyValue($instance, $propertyName, $value);
                } else {
                    if ($className === ResponseHeader::class) {
                        $classNameInstance = $classNameRef->newInstanceWithoutConstructor();
                        $this->unpackProtocol($className, $classNameInstance, $protocol);
                        $this->setTypePropertyValue($instance, $propertyName, $classNameInstance);
                    } else {
                        $wrapperProtocol = call_user_func([$className, 'getWrapperProtocol']);
                        $bytes = ProtocolTypeEnum::getCodeByText($wrapperProtocol);
                        $buffer = substr($protocol, 0, $bytes);
                        $protocol = substr($protocol, $bytes);
                        if ($className === Int64::class) {
                            $set = unpack($wrapperProtocol, $buffer);
                            $value = ($set[1] & 0xFFFFFFFF) << 32 | ($set[2] & 0xFFFFFFFF);
                        } else {
                            $value = unpack($wrapperProtocol, $buffer);
                        }
                        $value = is_array($value) ? array_shift($value) : $value;
                        if ($className === String16::class) {
                            $length = $value;
                            $value = substr($protocol, 0, $length);
                            $protocol = substr($protocol, $length);
                        }

                        $valueInstance = call_user_func([$className, 'value'], $value);

//                        echo "[-] {$className}\twrapperProtocol : {$wrapperProtocol}, name: {$propertyName}, value : " . $value . PHP_EOL;

                        $this->setTypePropertyValue($instance, $propertyName, $valueInstance);
                    }
                }
            }
        }
    }

    /**
     * @param ReflectionClass $refClass
     *
     * @return array
     */
    private function getProperties(ReflectionClass $refClass): array
    {
        $commonRefProperties = $refClass->getProperties(ReflectionProperty::IS_PROTECTED);
        $refProperties = $refClass->getProperties(ReflectionProperty::IS_PRIVATE);
        if (!empty($commonRefProperties)) {
            [$requestHeader, $size] = $commonRefProperties;
            array_unshift($refProperties, $requestHeader);
            array_unshift($refProperties, $size);
        }

        return $refProperties;
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
        if (!class_exists($className)) {
            if (Str::endsWith($shortClassName, 'Response')) {
                if ($protocolType === 'ResponseHeader') {
                    $className = Str::before($classNamespace, 'Response') . "Response\\Common\\{$protocolType}";
                } else {
                    $secondNamespace = Str::before($shortClassName, 'Response');
                    $className = "{$classNamespace}\\{$secondNamespace}\\{$protocolType}";
                }
            } else {
                $className = "{$classNamespace}\\{$protocolType}";
            }
        }

        if (!class_exists($className)) {
            throw new ProtocolTypeException('There are no protocol mines');
        }

        return $className;
    }

    /**
     * @param        $instance
     * @param string $propertyName
     * @param string $value
     *
     * @return mixed
     */
    private function setTypePropertyValue($instance, string $propertyName, $value = '')
    {
        $setMethod = Str::camel('set_' . $propertyName);
        $instance->{$setMethod}($value);

        return $instance;
    }
}
