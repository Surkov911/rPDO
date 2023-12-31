<?php
/**
 * This file is part of the rPDO package.
 *
 * Copyright (c) Jason Coward <jason@opengeek.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace rPDO\Test;

use rPDO\TestCase;
use rPDO\rPDO;

/**
 * Tests related to setting up the test environment
 *
 * @package rPDO\Test
 */
class SetUpTest extends TestCase
{
    /**
     * Test for a bogus false connection.
     *
     * @TODO Fix this, it seems to cause a timeout and a stall of PHPUnit.
     */
    public function testConnectionError()
    {
        $string_dsn = self::$properties[self::$properties['xpdo_driver'] . '_string_dsn_error'];
        $mypdo = new rPDO($string_dsn, "nonesuchuser", "nonesuchpass");
        $result = $mypdo->connect();
        // Should be an error set since we gave bogus info
        $this->assertTrue($result == false, "Connection was successful with bogus information.");
    }

    /**
     * Test to make sure any pre-existing test container is removed successfully.
     */
    public function testInitialize()
    {
        $xpdo = self::getInstance(true);
        if (is_object($xpdo)) {
            $response = $xpdo->getManager()->removeSourceContainer(
                rPDO::parseDSN(self::$properties[self::$properties['xpdo_driver'] . '_string_dsn_test'])
            );
            if ($response) {
                $xpdo = null;
            }
        }
        else {
            $xpdo = null;
        }
        $this->assertTrue($xpdo == null, "Test container exists and could not be removed for initialization");
    }

    /**
     * Verify test create database works.
     */
    public function testCreateSourceContainer()
    {
        $xpdo = self::getInstance(true);
        $created = $xpdo->getManager()->createSourceContainer();

        $this->assertTrue($created == true, "Could not create database.");
    }
}
