<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/11/15
 * Time: 3:12 AM
 */

namespace FileTest\Model;


use File\Model\File;
use File\Model\FileTable;
use Zend\Db\ResultSet\ResultSet;

class FileTableTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFileInfoCanRetrieveFileInfoUsingFileCodeFromFileModel()
    {
        $File = new File();
        $File->exchangeArray(
               array(
                   'file_id'         => 123,
                   'file_group_id'   => 1,
                   'origin'          => 'PRINT',
                   'vid'             => 0,
                   'cid'             => 1234,
                   'website_code'    => 'UP',
                   'name'            => 'print2.pdf',
                   'actual_filename' => '7637IMDEV3.pdf',
                   'extension'       => 'pdf',
                   'content_type'    => 'application/pdf',
                   'size'            => '3966640',
                   'created'         => '2012-02-18 07:34:03',
                   'deleted'         => '0',
                   'file_code'       => 'ecb65266627e8addb622025c923f10e5307ef81a',
                   'from_migration'  => '2',
               )

        );

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new File());
        $resultSet->initialize(array($File));

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
            ->with(array("file_code" => 'ecb65266627e8addb622025c923f10e5307ef81a'));

        //$resultSet = new ResultSet();
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getSql', 'selectWith'),
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

        $FileTable = new FileTable($mockTableGateway);
        $result = $FileTable->getFileInfo("ecb65266627e8addb622025c923f10e5307ef81a");
        $this->assertSame($File, $result);

    }
} 