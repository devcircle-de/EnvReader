<?php

declare(strict_types=1);

namespace devcirclede\EnvReader\Test;

use devcirclede\EnvReader\EnvParser;
use devcirclede\EnvReader\Exception\NotFoundException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EnvParserTest extends TestCase
{
    public static function providerTestParseWillPass(): array
    {
        return [
            'Get Env as String' => [
                'SOME_ENV',
                'text',
                'string',
                'text',
            ],
            'Get Env as Int' => [
                'SOME_ENV',
                '1234',
                'integer',
                1234,
            ],
            'Get Env as Float' => [
                'SOME_ENV',
                '1234.56',
                'float',
                1234.56,
            ],
            'Get Env ON as Bool' => [
                'SOME_ENV',
                'ON',
                'boolean',
                true
            ],
            'Get Env "false" as Bool' => [
                'SOME_ENV',
                'false',
                'boolean',
                false
            ],
            'Get Env array as Array' => [
                'SOME_ENV',
                '[1,a,2,b,3,c]',
                'array',
                [1, 'a', 2, 'b', 3, 'c'],
            ],
            'Get Env as JSON' => [
                'SOME_ENV',
                '{"foo": "bar"}',
                'json',
                ["foo" => "bar"],
            ],
            'Get Env as JSON #2' => [
                'SOME_ENV',
                '{"foo": "bar", "baz": [1,2,3,4,5,6,7.23]}',
                'json',
                ["foo" => "bar", "baz" => [1,2,3,4,5,6,7.23]],
            ],
        ];
    }

    #[DataProvider('providerTestParseWillPass')]
    public function testParseWillPassWithPutenv(?string $env, mixed $value, string $type, mixed $expected): void
    {
        putenv("$env=$value");
        $envValue = EnvParser::getInstance()->parse($env, $type);
        $this->assertSame($expected, $envValue);
        putenv($env);
    }

    #[DataProvider('providerTestParseWillPass')]
    public function testParseWillPassWithENV(?string $env, mixed $value, string $type, mixed $expected): void
    {
        $_ENV[$env] = $value;
        $envValue = EnvParser::getInstance()->parse($env, $type);
        $this->assertSame($expected, $envValue);
        unset($_ENV[$env]);
    }

    #[DataProvider('providerTestParseWillPass')]
    public function testParseWillPassWithSERVER(?string $env, mixed $value, string $type, mixed $expected): void
    {
        $_SERVER[$env] = $value;
        $envValue = EnvParser::getInstance()->parse($env, $type);
        $this->assertSame($expected, $envValue);
        unset($_SERVER[$env]);
    }

    public function testParseWillReturnNullIfNotExists(): void
    {
        $envValue = EnvParser::getInstance()->parse('NOT_EXISTS', 'string');
        $this->assertNull($envValue);
    }

    public function testParseWillFail(): void
    {
        putenv("SOME_ENV=WERT");
        try {
            EnvParser::getInstance()->parse('SOME_ENV', 'custom_type');
            $this->fail('Get must fail if type not registered');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(NotFoundException::class, $exception);
        } finally {
            putenv('SOME_ENV');
        }
    }
}
