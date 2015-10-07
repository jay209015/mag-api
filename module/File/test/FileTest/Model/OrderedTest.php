<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 1:55 AM
 */

namespace FileTest\Model;


use File\Model\Ordered;

class OrderedTest extends \PHPUnit_Framework_TestCase
{
    public function testOrderedInitialState()
    {
        $Ordered = new Ordered();

        $this->assertNull($Ordered->ordered_id);
        $this->assertNull($Ordered->job_order_id);
        $this->assertNull($Ordered->job_item_id);
        $this->assertNull($Ordered->file_id);
        $this->assertNull($Ordered->order_date);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $Ordered = new Ordered();
        $data  = array(
            'ordered_id'   => '1',
            'file_id'      => 'dsafudsa987fds8ouoifjew9',
            'job_order_id' => 123,
            'job_item_id'  => 456,
            'order_date'   => '2015-03-10 00:00:00'
        );

        $Ordered->exchangeArray($data);

        $this->assertSame($data['ordered_id'], $Ordered->ordered_id);
        $this->assertSame($data['file_id'], $Ordered->file_id);
        $this->assertSame($data['job_order_id'], $Ordered->job_order_id);
        $this->assertSame($data['job_item_id'], $Ordered->job_item_id);
        $this->assertSame($data['order_date'], $Ordered->order_date);

    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $Ordered = new Ordered();
        $data  = array(
            'ordered_id'   => '1',
            'file_id'      => 'dsafudsa987fds8ouoifjew9',
            'job_order_id' => 123,
            'job_item_id'  => 456,
            'order_date'   => '2015-03-10 00:00:00'
        );
        $Ordered->exchangeArray($data);
        $Ordered->exchangeArray(array());

        $this->assertNull($Ordered->ordered_id);
        $this->assertNull($Ordered->file_id);
        $this->assertNull($Ordered->job_order_id);
        $this->assertNull($Ordered->job_item_id);
        $this->assertNull($Ordered->order_date);
    }

} 