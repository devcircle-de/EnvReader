<?php

declare(strict_types=1);

namespace DevCircleDe\EnvReader;

use DevCircleDe\EnvReader\Exception\NotFoundException;
use DevCircleDe\EnvReader\Types\TypeCollection;
use DevCircleDe\EnvReader\Types\TypeCollectionInterface;

/**
 * @psalm-api
 */
interface EnvParserInterface
{
    public static function create(): EnvParserInterface;

    public function getCollection(): TypeCollectionInterface;

    public function parse(string $env, string $type): mixed;
}
