<?php

namespace Phlib\Config;

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

        $this->assertEquals('value', Config::get($config, 'one.two.three'));
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

        $this->assertEquals(['three' => 'value'], Config::get($config, 'one.two'));
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

        $this->assertEquals(['hello', 'world', 'foo', 'bar'], Config::get($config, 'one.two.three'));
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

        $this->assertEquals('world', Config::get($config, 'one.two.three.1'));
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

        $this->assertEquals('value', Config::get($config, 'one.two.three.four', 'value'));
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

        $returnVal = Config::set($config, 'one.twob', 'hello world');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
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

        $returnVal = Config::set($config, 'one.twob', ['threeb' => 'foo bar']);

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
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

        $returnVal = Config::set($config, 'one.two.three', 'hello world');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
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

        $returnVal = Config::set($config, 'one.two.three', ['four' => 'hello world']);

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
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

        $returnVal = Config::forget($config, 'one.two.three');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
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

        $returnVal = Config::forget($config, 'one.two');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
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

        $this->assertEquals($expected, Config::override($defaultConfig, $configOverride));
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

        $this->assertEquals($expected, Config::override($defaultConfig, $configOverride));
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

        $this->assertEquals($expected, Config::override($defaultConfig, $configOverride));
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

        $this->assertEquals($expected, Config::override($defaultConfig, $configOverride));
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

        $this->assertEquals($expected, Config::flatten($config));
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
                'twoA' => [
                    'one',
                    'two',
                    'three'
                ],
                'other' => 'value'
            ]
        ];

        $this->assertEquals($expected, Config::expand($flatConfig));
    }
}
