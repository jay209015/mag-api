<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 2:03 AM
 */

namespace FileTest\Model;


use File\Model\Ordered;
use File\Model\OrderedTable;
use Zend\Db\ResultSet\ResultSet;

class OrderedTableTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckOrderedIfExistCanRetrieveRecordsUsingOrderedModel()
    {
        $Ordered = new Ordered();
        $Ordered->exchangeArray(array(
            "file_id" => 2,
            "job_order_id" =>3,
            "job_item_id" => 3,
            "order_date" => "2008-08-12 00:00:00"
        ));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Ordered());
        $resultSet->initialize(array($Ordered));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array(
                "file_id"      => 2,
                "job_order_id" => 3,
                "job_item_id"  => 3,
                "order_date"   => "2008-08-12 00:00:00"
            ))
            ->will($this->returnValue($resultSet));

        $OrderedTable = new OrderedTable($mockTableGateway);
        $actualResult = $OrderedTable->checkOrderedIfExist($Ordered);
        $this->assertSame($Ordered, $actualResult);
    }

    public function testSaveOrderedWillInsertOrderedDataIfRecordDoesNotExist()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Ordered());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select', 'insert', 'getLastInsertValue'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array(
                "file_id"      => 2,
                "job_order_id" => 3,
                "job_item_id"  => 3,
                "order_date"   => "2008-08-12 00:00:00"
            ))
            ->will($this->returnValue($resultSet));

        $orderedData = array(
            "file_id" => 2,
            "file_code" => "ecb65266627e8addb622025c923f10e5307ef81a",
            "job_order_id" =>3,
            "job_item_id" => 3,
            "order_date" => "2008-08-12 00:00:00"
        );
        $Ordered = new Ordered();
        $Ordered->exchangeArray($orderedData);

        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($orderedData);

        $mockTableGateway->expects($this->once())
            ->method('getLastInsertValue')
            ->will($this->returnValue(3));


        $OrderedTable = new OrderedTable($mockTableGateway);
        $this->assertEquals(3, $OrderedTable->saveOrdered($Ordered));

    }
} 