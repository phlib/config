<?php

namespace Phlib\Tests;

use Phlib\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testGetStringValue()
    {
        $config = array(
            'one' => array(
                'two' => array(
                    'three' => 'value'
                )
            )
        );

        $this->assertEquals(
            'value',
            Config::get($config, 'one.two.three')
        );
    }

    public function testGetSection()
    {
        $config = array(
            'one' => array(
                'two' => array(
                    'three' => 'value'
                )
            )
        );

        $this->assertEquals(
            array(
                'three' => 'value'
            ),
            Config::get($config, 'one.two')
        );
    }

    public function testGetArrayValue()
    {
        $config = array(
            'one' => array(
                'two' => array(
                    'three' => array(
                        'hello',
                        'world',
                        'foo',
                        'bar'
                    )
                )
            )
        );

        $this->assertEquals(
            array(
                'hello',
                'world',
                'foo',
                'bar'
            ),
            Config::get($config, 'one.two.three')
        );
    }

    public function testGetValueByIndex()
    {
        $config = array(
            'one' => array(
                'two' => array(
                    'three' => array(
                        'hello',
                        'world',
                        'foo',
                        'bar'
                    )
                )
            )
        );

        $this->assertEquals(
            'world',
            Config::get($config, 'one.two.three.1')
        );
    }

    public function testGetDefault()
    {
        $config = array(
            'one' => array(
                'two' => array(
                    'three' => 'failed'
                )
            )
        );

        $this->assertEquals(
            'value',
            Config::get($config, 'one.two.three.four', 'value')
        );
    }

    public function testSetString()
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
                ),
                'twob' => 'hello world'
            )
        );

        $returnVal = Config::set($config, 'one.twob', 'hello world');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
    }

    public function testSetArray()
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
                ),
                'twob' => array(
                    'threeb' => 'foo bar'
                )
            )
        );

        $returnVal = Config::set($config, 'one.twob', array('threeb' => 'foo bar'));

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
    }

    public function testSetReplaceString()
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
                    'three' => 'hello world'
                )
            )
        );

        $returnVal = Config::set($config, 'one.two.three', 'hello world');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
    }

    public function testSetReplaceArray()
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
                    'three' => array(
                        'four' => 'hello world'
                    )
                )
            )
        );

        $returnVal = Config::set($config, 'one.two.three', array('four' => 'hello world'));

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
    }

    public function testUnsetValue()
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

                )
            )
        );

        $returnVal = Config::forget($config, 'one.two.three');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
    }

    public function testUnsetSection()
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

            )
        );

        $returnVal = Config::forget($config, 'one.two');

        $this->assertEquals($expected, $returnVal);
        $this->assertEquals($expected, $config);
    }

    public function testOverrideBasic()
    {
        $defaultConfig = array(
            'one' => 'hello world',
            'two' => 'foo bar'
        );
        $configOverride = array(
            'one'   => 'changed',
            'three' => 'added'
        );

        $expected = array(
            'one' => 'changed',
            'two' => 'foo bar',
            'three' => 'added'
        );

        $this->assertEquals($expected, Config::override($defaultConfig, $configOverride));
    }

    public function testOverrideNested()
    {
        $defaultConfig = array(
            'one' => 'hello world',
            'twoA' => array(
                'twoB' => array(
                    'twoCa' => 'hello world',
                    'twoCb' => 'look ma'
                )
            ),
            'three' => array(
                'nested'
            )
        );
        $configOverride = array(
            'twoA' => array(
                'twoB' => array(
                    'twoCa' => 'foo bar',
                    'twoCd' => 'new value'
                )
            ),
            'four' => 'added'
        );

        $expected = array(
            'one' => 'hello world',
            'twoA' => array(
                'twoB' => array(
                    'twoCa' => 'foo bar',
                    'twoCb' => 'look ma',
                    'twoCd' => 'new value'
                )
            ),
            'three' => array(
                'nested'
            ),
            'four' => 'added'
        );

        $this->assertEquals($expected, Config::override($defaultConfig, $configOverride));
    }

    public function testOverrideArrayValues()
    {
        $defaultConfig = array(
            'one' => 'hello world',
            'two' => array(
                'twoA' => array(
                    'one',
                    'two',
                    'three'
                ),
                'other' => 'value'
            )
        );
        $configOverride = array(
            'one' => 'foo bar',
            'two' => array(
                'twoA' => array(
                    'four',
                    'five',
                    'six'
                )
            )
        );

        $expected = array(
            'one' => 'foo bar',
            'two' => array(
                'twoA' => array(
                    'four',
                    'five',
                    'six'
                ),
                'other' => 'value'
            )
        );

        $this->assertEquals($expected, Config::override($defaultConfig, $configOverride));
    }

    public function testFlatten()
    {
        $config = array(
            'one' => 'hello world',
            'two' => array(
                'twoA' => array(
                    'one',
                    'two',
                    'three'
                ),
                'other' => 'value'
            )
        );

        $expected = array(
            'one'        => 'hello world',
            'two.twoA.0' => 'one',
            'two.twoA.1' => 'two',
            'two.twoA.2' => 'three',
            'two.other'  => 'value'
        );

        $this->assertEquals($expected, Config::flatten($config));
    }

    public function testExpand()
    {
        $flatConfig = array(
            'one'        => 'hello world',
            'two.twoA.0' => 'one',
            'two.twoA.1' => 'two',
            'two.twoA.2' => 'three',
            'two.other'  => 'value'
        );

        $expected = array(
            'one' => 'hello world',
            'two' => array(
                'twoA' => array(
                    'one',
                    'two',
                    'three'
                ),
                'other' => 'value'
            )
        );

        $this->assertEquals($expected, Config::expand($flatConfig));
    }
}
