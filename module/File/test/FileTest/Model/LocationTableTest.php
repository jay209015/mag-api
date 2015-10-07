<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/13/15
 * Time: 3:42 AM
 */

namespace FileTest\Model;


use File\Model\Location;
use File\Model\LocationTable;
use Zend\Db\ResultSet\ResultSet;

class LocationTableTest extends \PHPUnit_Framework_TestCase
{
    public function testSaveLocationCanInsertRecord()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Location());
        $resultSet->initialize(array());

        $mockSql = $this->getMock(
            'Zend\Db\Sql\Select',
            array('select', 'where'),
            array(),
            '',
            false
        );
        $mockSql->expects($this->once())
            ->method('select')
            ->will($this->returnValue($mockSql));

        $mockSql->expects($this->once())
            ->method('where')
            ->with(array(
                "file_id"    => 32,
                "server"     => "S3",
            ));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getSql', 'selectWith', 'insert', 'getLastInsertValue'),
            array(),
            '',
            false
        );

        $mockTableGateway->expects($this->once())
            ->method('getSql')
            ->will($this->returnValue($mockSql));

        $mockTableGateway->expects($this->once())
            ->method('selectWith')
            ->with($mockSql)
            ->will($this->returnValue($resultSet));

        $locationData = array(
            "server"     => "S3",
            "path"       => "888abf-1408504715-5095IMDEV3.pdf",
            "file_id"    => 32,
            "file_code" => "ecb65266627e8addb622025c923f10e5307ef81a",
            "date_added" => date("Y-m-d H:i:s"),
        );
        $Location = new Location();
        $Location->exchangeArray($locationData);

        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($locationData);

        $mockTableGateway->expects($this->once())
            ->method('getLastInsertValue')
            ->will($this->returnValue(6));

        $LocationTable = new LocationTable($mockTableGateway);

        $this->assertEquals(6, $LocationTable->saveLocation($Location));

    }
} 