<?php

namespace Phlib\Config;

use Phlib\Config\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetStringValue()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $this->assertSame('value', get($config, 'one.two.three'));
    }

    public function testGetSection()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $this->assertSame(['three' => 'value'], get($config, 'one.two'));
    }

    public function testGetArrayValue()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => ['hello', 'world', 'foo', 'bar']
                ]
            ]
        ];

        $this->assertSame(['hello', 'world', 'foo', 'bar'], get($config, 'one.two.three'));
    }

    public function testGetValueByIndex()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => ['hello', 'world', 'foo', 'bar']
                ]
            ]
        ];

        $this->assertSame('world', get($config, 'one.two.three.1'));
    }

    public function testGetDefault()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'failed'
                ]
            ]
        ];

        $this->assertSame('value', get($config, 'one.two.three.four', 'value'));
    }

    public function testSetString()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ],
                'twob' => 'hello world'
            ]
        ];

        $returnVal = set($config, 'one.twob', 'hello world');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testSetArray()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ],
                'twob' => [
                    'threeb' => 'foo bar'
                ]
            ]
        ];

        $returnVal = set($config, 'one.twob', ['threeb' => 'foo bar']);

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testSetReplaceString()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => 'hello world'
                ]
            ]
        ];

        $returnVal = set($config, 'one.two.three', 'hello world');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testSetReplaceArray()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $expected = [
            'one' => [
                'two' => [
                    'three' => [
                        'four' => 'hello world'
                    ]
                ]
            ]
        ];

        $returnVal = set($config, 'one.two.three', ['four' => 'hello world']);

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetValue()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $expected = [
            'one' => [
                'two' => []
            ]
        ];

        $returnVal = forget($config, 'one.two.three');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetSection()
    {
        $config = [
            'one' => [
                'two' => [
                    'three' => 'value'
                ]
            ]
        ];

        $expected = [
            'one' => []
        ];

        $returnVal = forget($config, 'one.two');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetValueNotExist()
    {
        $config = array(
            'one' => array(
                'two' => array(
                    'three' => 'value'
                )
            )
        );

        $expected = array(
            'one' => array(
                'two' => array(
                    'three' => 'value'
                )
            )
        );

        $returnVal = forget($config, 'one.two.missing');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testUnsetSectionNotExist()
    {
        $config = array(
            'one' => array(
                'two' => array(
                    'three' => 'value'
                )
            )
        );

        $expected = array(
            'one' => array(
                'two' => array(
                    'three' => 'value'
                )
            )
        );

        $returnVal = forget($config, 'one.missing.three');

        $this->assertSame($expected, $returnVal);
        $this->assertSame($expected, $config);
    }

    public function testOverrideBasic()
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => 'foo bar'
        ];
        $configOverride = [
            'one'   => 'changed',
            'three' => 'added'
        ];

        $expected = [
            'one' => 'changed',
            'two' => 'foo bar',
            'three' => 'added'
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideNested()
    {
        $defaultConfig = [
            'one' => 'hello world',
            'twoA' => [
                'twoB' => [
                    'twoCa' => 'hello world',
                    'twoCb' => 'look ma'
                ]
            ],
            'three' => [
                'nested'
            ]
        ];
        $configOverride = [
            'twoA' => [
                'twoB' => [
                    'twoCa' => 'foo bar',
                    'twoCd' => 'new value'
                ]
            ],
            'four' => 'added'
        ];

        $expected = [
            'one' => 'hello world',
            'twoA' => [
                'twoB' => [
                    'twoCa' => 'foo bar',
                    'twoCb' => 'look ma',
                    'twoCd' => 'new value'
                ]
            ],
            'three' => [
                'nested'
            ],
            'four' => 'added'
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideArrayValues()
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one',
                    'two',
                    'three'
                ],
                'other' => 'value'
            ]
        ];
        $configOverride = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [
                    'four',
                    'five',
                    'six'
                ]
            ]
        ];

        $expected = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [
                    'four',
                    'five',
                    'six'
                ],
                'other' => 'value'
            ]
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideEmptyArrayValues()
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one',
                    'two',
                    'three'
                ],
                'other' => 'value'
            ]
        ];
        $configOverride = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => []
            ]
        ];

        $expected = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [],
                'other' => 'value'
            ]
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideEmptyArrayValuesIntoAssoc()
    {
        $defaultConfig = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3
                ],
                'other' => 'value'
            ]
        ];
        $configOverride = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => []
            ]
        ];

        $expected = [
            'one' => 'foo bar',
            'two' => [
                'twoA' => [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3
                ],
                'other' => 'value'
            ]
        ];

        $this->assertSame($expected, override($defaultConfig, $configOverride));
    }

    public function testOverrideWithMultipleParams()
    {
        $defaultConfig   = ['one' => 'hello world'];
        $configOverride1 = ['one' => 'foo bar'];
        $configOverride2 = ['one' => 'bar baz'];

        $this->assertSame($configOverride2, override($defaultConfig, $configOverride1, $configOverride2));
    }

    public function testOverrideWithNoOverrides()
    {
        $this->expectException(InvalidArgumentException::class);
        override([]);
    }

    public function testOverrideWithOneParam()
    {
        $this->expectException(InvalidArgumentException::class);
        $defaultConfig   = ['one' => 'hello world'];
        override($defaultConfig);
    }

    public function testIssue4FailToOverrideNonArray()
    {
        $arr1 = ['el1' => 'someValue'];
        $arr2 = ['el1' => ['subEl' => 'someValue']];
        $this->assertSame($arr2, override($arr1, $arr2));
    }

    public function testInverseIssue4FailToOverrideNonArray()
    {
        $arr1 = ['el1' => ['subEl' => 'someValue']];
        $arr2 = ['el1' => 'someValue'];
        $this->assertSame($arr2, override($arr1, $arr2));
    }

    public function testFlatten()
    {
        $config = [
            'one' => 'hello world',
            'two' => [
                'twoA' => [
                    'one',
                    'two',
                    'three'
                ],
                'other' => 'value'
            ]
        ];

        $expected = [
            'one'        => 'hello world',
            'two.twoA.0' => 'one',
            'two.twoA.1' => 'two',
            'two.twoA.2' => 'three',
            'two.other'  => 'value'
        ];

        $this->assertSame($expected, flatten($config));
    }

    public function testExpand()
    {
        $flatConfig = [
            'one'        => 'hello world',
            'two.twoA.0' => 'one',
            'two.twoA.1' => 'two',
            'two.twoA.2' => 'three',
            'two.other'  => 'value'
        ];

        $expected = [
            'one' => 'hello world',
            'two' => [
                'other' => 'value',
                'twoA' => [
                    'one',
                    'two',
                    'three'
                ],
            ]
        ];

        $this->assertSame($expected, expand($flatConfig));
    }
}
