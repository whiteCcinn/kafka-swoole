<?php
declare(strict_types=1);

namespace Kafka\Protocol\TraitStructure;

use Kafka\Protocol\Type\AbstractType;
use function is_array;
use function is_object;

trait ToArrayTrait
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $map = [];
        foreach ($this as $k => $v) {
            if ($v instanceof AbstractType) {
                $map[$k] = $v->getValue();
            } elseif (is_array($v)) {
                $this->forToArray($map[$k], $v);
            } else {
                foreach ($v->toArray() as $field => $item) {
                    $map[$k][$field] = $item;
                };
            }
        }

        return $map;
    }

    /**
     * @param       $k
     * @param array $v
     */
    private function forToArray(&$k, $v = [])
    {
        $map = [];
        if (is_array($v) || is_object($v)) {
            foreach ($v as $kk => $vv) {
                if ($vv instanceof AbstractType) {
                    $map[$kk] = $vv->getValue();
                } elseif (is_array($vv)) {
                    $this->forToArray($map[$kk], $vv);
                } else {
                    foreach ($vv->toArray() as $field => $item) {
                        $map[$kk][$field] = $item;
                    };
                }
            }
        }
        $k = $map;
    }
}
