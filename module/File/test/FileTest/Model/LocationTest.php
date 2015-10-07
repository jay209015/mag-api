<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 1:55 AM
 */

namespace FileTest\Model;


use File\Model\Location;

class LocationTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialState()
    {
        $location = new Location();

        $this->assertNull($location->location_id);
        $this->assertNull($location->file_id);
        $this->assertNull($location->server);
        $this->assertNull($location->path);
        $this->assertNull($location->old_path);
        $this->assertNull($location->date_added);
        $this->assertNull($location->file_code);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $location = new Location();
        $data  = array(
            'location_id'   => 1,
            'file_id'       => 1,
            'server'        => 'S3',
            'path'          => 'test.pdf',
            'old_path'      => '/home/mnlfiles/test.pdf',
            'file_code'     => 'dsafudsa987fds8ouoifjew9'
        );

        $location->exchangeArray($data);

        $this->assertSame($data['location_id'], $location->location_id);
        $this->assertSame($data['file_id'], $location->file_id);
        $this->assertSame($data['server'], $location->server);
        $this->assertSame($data['path'], $location->path);
        $this->assertSame($data['old_path'], $location->old_path);
        $this->assertSame($data['file_code'], $location->file_code);

    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $location = new Location();
        $location->exchangeArray(array());

        $this->assertNull($location->location_id);
        $this->assertNull($location->file_id);
        $this->assertNull($location->server);
        $this->assertNull($location->path);
        $this->assertNull($location->old_path);
        $this->assertNull($location->file_code);
    }

} 