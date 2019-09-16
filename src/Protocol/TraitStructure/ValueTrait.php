<?php
declare(strict_types=1);

namespace Kafka\Protocol\TraitStructure;

use Kafka\Protocol\Type\AbstractType;
use Kafka\Support\Str;
use \ReflectionException;
use \ReflectionClass;
use \ReflectionProperty;

trait ValueTrait
{
    /**
     * @var ReflectionProperty[] $refProperties
     */
    private $refProperties;

    /**
     * @param string $name
     * @param        $value
     *
     * @throws ReflectionException
     */
    public function __set(string $name, $value)
    {
        $refClass = new ReflectionClass(static::class);
        $refClassName = $refClass->getName();
        if ($this->refProperties === null) {
            $refProperties = $refClass->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED);
            /** @var ReflectionProperty $refProperty */
            foreach ($refProperties as $refProperty) {
                $this->refProperties[$refProperty->getName()] = [
                    'reflectionClass'    => new ReflectionClass($refProperty->getDeclaringClass()->getName()),
                    'reflectionProperty' => $refProperty,
                ];
            }
        }

        if (Str::endsWith($refClassName, 'Request') && $name === 'response') {
            $this->{$name} = $value;
        } else {
            /** @var ReflectionClass $reflectionClass */
            $reflectionClass = $this->refProperties[$name]['reflectionClass'];
            $refProperty = new ReflectionProperty($reflectionClass->getName(), $name);
            $typeNamespace = Str::pathToNamespace(dirname(Str::namespaceToPath(__NAMESPACE__))) . '\Type\\';

            $propertyComment = $refProperty->getDocComment();
            $propertyName = $refProperty->getName();
            if (preg_match('/.*@var\s+(?P<protocolType>\w+)(?P<isArray>\[\])?\s+.*/', $propertyComment,
                $matches)) {
                $isArray = isset($matches['isArray']) ? true : false;
                $className = "{$typeNamespace}{$matches['protocolType']}";

                var_dump($className);
                $typeValue = [];
                if ($isArray) {
                    if (!is_array($value)) {
                        throw new \Exception("[-] The {$name} value type must be is array");
                    }
                    foreach ($value as $v) {
                        $typeValue[] = call_user_func([$className, AbstractType::$setFunc], $v);
                    }
                } else {
                    $typeValue = call_user_func([$className, AbstractType::$setFunc], $value);
                }
                $setMethod = Str::camel('set_' . $propertyName);
                var_dump($setMethod,$typeValue);exit;
                $this->{$setMethod}($typeValue);
            }
        }
    }

    private function getClassName(string $typeNamespace, string $protocolTypeName): string
    {
        return "{$typeNamespace}{$protocolTypeName}";
    }
}
