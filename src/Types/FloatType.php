<?php

declare(strict_types=1);

namespace Freesoftde\EnvReader\Types;

use Freesoftde\EnvReader\Exception\ConvertionException;

class FloatType implements TypeInterface
{
    public function getName(): string
    {
        return 'float';
    }

    public function convert(mixed $value): float
    {
        if (false === ($filtered = filter_var($value, \FILTER_VALIDATE_FLOAT))) {
            throw new ConvertionException("Could not convert value $value to float.");
        }

        return $filtered;
    }
}
