<?php

declare(strict_types=1);

namespace Vansari\EnvReader\Types;

class StringType implements TypeInterface
{
    public function getName(): string
    {
        return 'string';
    }

    public function convert(string $value): string
    {
        return $value;
    }
}
