<?php

declare(strict_types=1);

namespace Freesoftde\EnvReader\Types;

use Freesoftde\EnvReader\Exception\KeyInUseException;
use Freesoftde\EnvReader\Exception\NotFoundException;

class TypeCollection
{
    private array $collection = [];

    public function __construct(TypeInterface ...$types)
    {
        foreach ($types as $type) {
            $this->addItem($type);
        }
    }

    public function addItem(TypeInterface $type, bool $overwrite = false): self
    {
        if (array_key_exists($type->getName(), $this->collection) && !$overwrite) {
            throw new KeyInUseException("Key '{$type->getName()}' already exists.");
        }

        $this->collection[$type->getName()] = $type;

        return $this;
    }

    public function getItem(string $key): TypeInterface
    {
        if (!array_key_exists($key, $this->collection)) {
            throw new NotFoundException($key . ' is not present in collection.');
        }

        return $this->collection[$key];
    }

    public function getKeys(): array
    {
        return array_keys($this->collection);
    }
}
