<?php

declare(strict_types=1);

namespace Phlib\Config;

use Phlib\Config\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetStringValue(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $this->assertSame('value', get($config, 'one.two.three'));
    }

    public function testGetSection(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $this->assertSame(['three' => 'value'], get($config, 'one.two'));
    }

    public function testGetArrayValue(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => ['hello', 'world', 'foo', 'bar'],
                ],
            ],
        ];

        $this->assertSame(['hello', 'world', 'foo', 'bar'], get($config, 'one.two.three'));
    }

    public function testGetValueByIndex(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => ['hello', 'world', 'foo', 'bar'],
                ],
            ],
        ];

        $this->assertSame('world', get($config, 'one.two.three.1'));
    }

    public function testGetDefault(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'failed',
                ],
            ],
        ];

        $this->assertSame('value', get($config, 'one.two.three.four', 'value'));
    }

    public function testSetString(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
                'twob' => 'hello world',
            ],
        ];

        $returnVal = set($config, 'one.twob', 'hello world');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testSetArray(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
                'twob' => [
                    'threeb' => 'foo bar',
                ],
            ],
        ];

        $returnVal = set($config, 'one.twob', ['threeb' => 'foo bar']);

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testSetReplaceString(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'hello world',
                ],
            ],
        ];

        $returnVal = set($config, 'one.two.three', 'hello world');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testSetReplaceArray(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => [
                        'four' => 'hello world',
                    ],
                ],
            ],
        ];

        $returnVal = set($config, 'one.two.three', ['four' => 'hello world']);

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetValue(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [
                'two' => [],
            ],
        ];

        $returnVal = forget($config, 'one.two.three');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetSection(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [],
        ];

        $returnVal = forget($config, 'one.two');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetValueNotExist(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $returnVal = forget($config, 'one.two.missing');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetSectionNotExist(): void
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'value',
                ],
            ],
        ];

        $returnVal = forget($config, 'one.missing.three');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testOverrideBasic(): void
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => 'foo bar',
        ];
        $configOverride = [
            'one' => 'changed',
            'three' => 'added',
        ];

        $expected = [
            'one' => 'changed',
            'two' => 'foo bar',
            'three' => 'added',
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideNested(): void
    {
        $defaultConfig = [
            'one' => 'hello world',
            'twoA' => [
                'twoB' => [
                    'twoCa' => 'hello world',
                    'twoCb' => 'look ma',
                ],
            ],
            'three' => [
                'nested',
            ],
        ];
        $configOverride = [
            'twoA' => [
                'twoB' => [
                    'twoCa' => 'foo bar',
                    'twoCd' => 'new value',
                ],
            ],
            'four' => 'added',
        ];

        $expected = [
            'one' => 'hello world',
            'twoA' => [
                'twoB' => [
                    'twoCa' => 'foo bar',
                    'twoCb' => 'look ma',
                    'twoCd' => 'new value',
                ],
            ],
            'three' => [
                'nested',
            ],
            'four' => 'added',
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideArrayValues(): void
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one',
                    'two',
                    'three',
                ],
                'other' => 'value',
            ],
        ];
        $configOverride = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [
                    'four',
                    'five',
                    'six',
                ],
            ],
        ];

        $expected = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [
                    'four',
                    'five',
                    'six',
                ],
                'other' => 'value',
            ],
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideEmptyArrayValues(): void
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one',
                    'two',
                    'three',
                ],
                'other' => 'value',
            ],
        ];
        $configOverride = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [],
            ],
        ];

        $expected = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [],
                'other' => 'value',
            ],
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideEmptyArrayValuesIntoAssoc(): void
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                ],
                'other' => 'value',
            ],
        ];
        $configOverride = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [],
            ],
        ];

        $expected = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                ],
                'other' => 'value',
            ],
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideWithMultipleParams(): void
    {
        $defaultConfig = ['one' => 'hello world'];
        $configOverride1 = ['one' => 'foo bar'];
        $configOverride2 = ['one' => 'bar baz'];

        $this->assertSame($configOverride2, override($defaultConfig, $configOverride1, $configOverride2));
    }

    public function testOverrideWithNoOverrides(): void
    {
        $this->expectException(InvalidArgumentException::class);
        override([]);
    }

    public function testOverrideWithOneParam(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $defaultConfig = ['one' => 'hello world'];
        override($defaultConfig);
    }

    public function testIssue4FailToOverrideNonArray(): void
    {
        $arr1 = ['el1' => 'someValue'];
        $arr2 = ['el1' => ['subEl' => 'someValue']];
        $this->assertSame($arr2, override($arr1, $arr2));
    }

    public function testInverseIssue4FailToOverrideNonArray(): void
    {
        $arr1 = ['el1' => ['subEl' => 'someValue']];
        $arr2 = ['el1' => 'someValue'];
        $this->assertSame($arr2, override($arr1, $arr2));
    }

    public function testFlatten(): void
    {
        $config = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one',
                    'two',
                    'three',
                ],
                'other' => 'value',
            ],
        ];

        $expected = [
            'one' => 'hello world',
            'two.twoA.0' => 'one',
            'two.twoA.1' => 'two',
            'two.twoA.2' => 'three',
            'two.other' => 'value',
        ];

        $this->assertSame($expected, flatten($config));
    }

    public function testExpand(): void
    {
        $flatConfig = [
            'one' => 'hello world',
            'two.twoA.0' => 'one',
            'two.twoA.1' => 'two',
            'two.twoA.2' => 'three',
            'two.other' => 'value',
        ];

        $expected = [
            'one' => 'hello world',
            'two' => [
                'other' => 'value',
                'twoA' => [
                    'one',
                    'two',
                    'three',
                ],
            ],
        ];

        $this->assertSame($expected, expand($flatConfig));
    }
}
