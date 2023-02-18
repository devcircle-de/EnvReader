<?php
declare(strict_types=1);

namespace Freesoftde\EnvReader;

use Freesoftde\EnvReader\Types\StringType;
use Freesoftde\EnvReader\Types\TypeCollection;

class Env
{
    private static ?Env $instance = null;
    private TypeCollection $collection;

    private function __construct() {
        $this->collection = new TypeCollection(
            new StringType()
        );
    }

    public function getCollection(): TypeCollection
    {
        return $this->collection;
    }

    private function __clone() {}

    private function __wakeup() {}

    public static function getInstance(): static
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function get(string $env, string $type): mixed
    {
        if (false === (bool)($value = $_ENV[$env] ?? $_SERVER[$env] ?? getenv($env))) {
            return null;
        }

        return $this->collection->getItem($type)->get($value);
    }
}