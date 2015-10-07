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

class FileLocationControllerTest extends AbstractHttpControllerTestCase
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
            'server' => 'S3',
        );

        $this->actual_file = array(
            "file_id"           => 1,
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

        $this->actual_location = array(
            'server' => 'S3',
            'path' => 'test.pdf',
            'old_path' => null,
            'date_added' => null
        );

        $fileModel = $this->getMock('File\Model\File', array('exchangeArray'), array(), '', null);
        $fileModel->expects($this->any())
            ->method('exchangeArray');
        $this->serviceManager->setService('File', $fileModel);

        $fileTable = $this->getMock('File\Model\FileTableSlave', array('getFileInfo', 'transferOwnership'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(new JsonModel($this->actual_file)));
        $this->serviceManager->setService('FileTableSlave', $fileTable);

        $fileService = $this->getMock('File\Service\FileServiceSlave', array('getFileInfo'), array(), '', null);
        $fileService->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(new JsonModel($this->actual_file)));
        $this->serviceManager->setService('FileServiceSlave', $fileService);

        $locationModel = $this->getMock('File\Model\Location', array('exchangeArray'), array(), '', null);
        $locationModel->expects($this->any())
            ->method('exchangeArray');
        $this->serviceManager->setService('Location', $locationModel);

        $locationTable = $this->getMock('File\Model\LocationTableSlave', array('getLocation'), array(), '', null);
        $locationTable->expects($this->any())
            ->method('getLocation')
            ->will($this->returnValue(new JsonModel($this->actual_location)));
        $this->serviceManager->setService('LocationTableSlave', $locationTable);

        $validator = $this->getMock('File\Validation\LocationValidation', array('validate', 'shouldHaveFileId', 'shouldHaveServerCode'), array(), '', null);
        $validator->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(new JsonModel(array('valid' => true, 'errors' => ''))));
        $this->serviceManager->setService('LocationValidation', $validator);
    }

    public function testGetFileLocationCanBeAccessed()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->dispatch('/file/getFileLocation', 'GET', $this->data);
        $this->assertResponseStatusCode(200);

    }

    public function testGetFileLocationReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/file/getFileLocation', 'GET', $this->data);
        $this->assertResponseStatusCode(401);
    }

    public function testCanGetFileLocation()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');

        $input = $this->actual_file;
        $input['location'] = array( (object) $this->actual_location);
        $expected = (object) $input;

        $this->dispatch('/file/getFileLocation', 'GET', $this->data);
        $response = $this->getResponse();
        $actual = $response->getContent();
        $this->assertEquals($expected, json_decode($actual));
    }

}