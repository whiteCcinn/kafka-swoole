<?php
declare(strict_types=1);

namespace Kafka\Protocols;

use function array_map;
use function array_shift;
use function array_values;
use function count;
use function gzdecode;
use function gzencode;
use function hex2bin;
use function in_array;
use function is_array;
use function pack;
use function sprintf;
use function strlen;
use function substr;
use function unpack;
use function version_compare;

abstract class AbstractProtocol
{

}
