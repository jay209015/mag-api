<?php
/**
 * Created by PhpStorm.
 * User: Oscar Baccay <oscar.b@uprinting.com>
 */

namespace FileTest\FileTest\Controller;

use Zend\View\Model\JsonModel;
use FileTest\Bootstrap;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class FileInfoControllerTest extends AbstractHttpControllerTestCase
{
    protected $serviceManager;

    protected function setUp()
    {
        putenv('APPLICATION_ENV=local');
        $this->setApplicationConfig(Bootstrap::getConfig());
        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);

        $this->data = array(
            'id' => '9999',
        );

        $this->file_data = array(
            "file_id"           => 1,
            "vid"               => 1,
            "cid"               => 1,
            "name"              => "test.pdf",
            "actual_filename"   => "test-100.pdf",
            "content_type"      => "application/pdf",
            "size"              => 100,
            "extension"         => "pdf",
            "origin"            => "SITE",
            "created"           => "2015-03-12 00:00:00",
            "file_code"         => "a90a",
            "_id"               => "a90a",
        );

        $this->location_data = array(
            'location_id'   => 1,
            'file_id'       => 1,
            'server'        => 'S3',
            'path'          => 'test.pdf',
            'old_path'      => null,
            'date_added'    => null
        );

        $this->pages_data = array(
            'page'          => 1,
            'width'         => 200,
            'height'        => 200,
            'colorspace'    => 'CMYK',
        );

        $this->ordered_data = array(
            'job_order_id'  => 1,
            'job_item_id'   => 2,
            'order_date'    => '2015-03-13 01:01:01',
        );

        $this->thumbnail_data = array(
            'page'  => 1,
            'width' => 200,
            'path'  => 'thumbnail-1.jpg',
        );

        $this->mockup_data = array(
            'page'  => 1,
            'width' => 200,
            'path'  => 'thumbnail-1.jpg',
        );

        // file
        $fileTable = $this->getMock('File\Model\FileTable', array('getFileInfo', 'transferOwnership', 'getFiles'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(new JsonModel($this->file_data)));
        $this->serviceManager->setService('FileTable', $fileTable);

        $this->file_data['location'] = array($this->location_data);
        $this->file_data['pages'] = array($this->pages_data);
        $fileService = $this->getMock('File\Service\FileService', array('getFileDocument'), array(), '', null);
        $fileService->expects($this->any())
            ->method('getFileDocument')
            ->will($this->returnValue(array(
                'files'     => array($this->file_data),
                'ordered'   => array($this->ordered_data),
                'thumbnail' => array($this->thumbnail_data),
                'mockup'    => array($this->mockup_data),
            ))
        );
        $this->serviceManager->setService('FileService', $fileService);

/*
        // pages
        $pageTable = $this->getMock('File\Model\PageTable', array('getPages'), array(), '', null);
        $pageTable->expects($this->any())
            ->method('getPages')
            ->will($this->returnValue(array( (object) $this->pages_data )));
        $this->serviceManager->setService('PageTable', $pageTable);

        // ordered
        $orderedTable = $this->getMock('File\Model\OrderedTable', array('getOrdered', 'checkOrderedIfExist', 'saveOrdered'), array(), '', null);
        $orderedTable->expects($this->any())
            ->method('getOrdered')
            ->will($this->returnValue(new JsonModel($this->ordered_data)));
        $this->serviceManager->setService('OrderedTable', $orderedTable);

        // thumbnail
        $thumbnailTable = $this->getMock('File\Model\ThumbnailTable', array('getThumbnails'), array(), '', null);
        $thumbnailTable->expects($this->any())
            ->method('getThumbnails')
            ->will($this->returnValue(new JsonModel($this->thumbnail_data)));
        $this->serviceManager->setService('ThumbnailTable', $thumbnailTable);

        // mockup
        $mockupTable = $this->getMock('File\Model\MockupTable', array('getMockups'), array(), '', null);
        $mockupTable->expects($this->any())
            ->method('getMockups')
            ->will($this->returnValue(new JsonModel($this->mockup_data)));
        $this->serviceManager->setService('MockupTable', $mockupTable);
*/
    }

    public function testGetFileInfoCanBeAccessed()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->dispatch('/file/getFileInfo/5b96bea280fe8e4e848a694d0ed1b8538ffd7408', 'GET');
        $this->assertResponseStatusCode(200);

    }

    public function testGetFileInfoReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/file/getFileInfo/5b96bea280fe8e4e848a694d0ed1b8538ffd7408', 'GET');
        $this->assertResponseStatusCode(401);
    }

    public function testCanGetFileInfo()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');

        $expected = json_encode(array(
            'files'     => array($this->file_data),
            'ordered'   => array($this->ordered_data),
            'thumbnail' => array($this->thumbnail_data),
            'mockup'    => array($this->mockup_data),
        ));

        $this->dispatch('/file/getFileInfo/5b96bea280fe8e4e848a694d0ed1b8538ffd7408', 'GET');
        $response = $this->getResponse();
        $actual = $response->getContent();
        $this->assertEquals($expected, $actual);
    }

}