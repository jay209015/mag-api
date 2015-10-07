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

class LocationControllerTest extends AbstractHttpControllerTestCase
{
    protected $serviceManager;
    protected $stubLocation;
    protected $actual_file;
    protected $actual_location;
    protected $data;

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

        $this->stubLocation = json_encode(
            array(
                "location" => array(
                    "file_id" => 1,
                    "server" => "S3",
                    "path" => "888abf-1408504715-5095IMDEV3.pdf",
                    "old_path" => "/home/mnlfile/printfile/0/5095IMDEV3.pdf",
                    "migrated" => "1",
                    "date_added" => "2012-02-13T10:17:55.000Z",
                    "file_code" => "888abf14085047155095IMDEV3"
                )
            )
        );

        $this->actual_file = array("file_id" => 1);
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

        $fileTable = $this->getMock('File\Model\FileTable', array('getFileInfo', 'transferOwnership'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(new JsonModel($this->actual_file)));
        $this->serviceManager->setService('FileTable', $fileTable);

        $fileTable = $this->getMock('File\Model\FileTableSlave', array('getFileInfo', 'transferOwnership'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(new JsonModel($this->actual_file)));
        $this->serviceManager->setService('FileTableSlave', $fileTable);

        $locationModel = $this->getMock('File\Model\Location', array('exchangeArray'), array(), '', null);
        $locationModel->expects($this->any())
            ->method('exchangeArray');
        $this->serviceManager->setService('Location', $locationModel);

        $locationTable = $this->getMock('File\Model\LocationTableSlave', array('getLocation','saveLocation'), array(), '', null);
        $locationTable->expects($this->any())
            ->method('getLocation')
            ->will($this->returnValue(new JsonModel($this->actual_location)));
        $locationTable->expects($this->any())
            ->method('saveLocation')
            ->will($this->returnValue(3));
        $this->serviceManager->setService('LocationTableSlave', $locationTable);

        $locationTable = $this->getMock('File\Model\LocationTable', array('getLocation','saveLocation'), array(), '', null);
        $locationTable->expects($this->any())
            ->method('getLocation')
            ->will($this->returnValue(new JsonModel($this->actual_location)));
        $locationTable->expects($this->any())
            ->method('saveLocation')
            ->will($this->returnValue(3));
        $this->serviceManager->setService('LocationTable', $locationTable);

    }

    public function testGetLocationCanBeAccessed()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->dispatch('/file/getLocation', 'GET', $this->data);
        $this->assertResponseStatusCode(200);

    }

    public function testGetLocationReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/file/getLocation?id=9999&server=S3', 'GET', $this->data);
        $this->assertResponseStatusCode(401);
    }

    public function testCanGetLocation()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');

        $expected = (object) $this->actual_location;

        $this->dispatch('/file/getLocation', 'GET', $this->data);
        $response = $this->getResponse();
        $actual = $response->getContent();
        $this->assertEquals($expected, json_decode($actual));
    }

    public function testCreateReturns401StatusCodeIfClientIsNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/file/setLocation/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(401);
    }

    public function testCreateReturns409StatusCodeIfLocationObjectIsMissing()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/file/setLocation/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(409);
    }

    public function testCreateReturns409StatusCodeIfLocationObjectHasMissingProperties()
    {
        $LocationFilter = $this->getMock('File\Filter\LocationFilter', array('setData', 'isValid'), array(), '', null);
        $LocationFilter->expects($this->any())->method('setData')->with($this->stubLocation);
        $LocationFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));
        $this->serviceManager->setService('LocationFilter', $LocationFilter);

        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array("location" => array("server" => "S3"))));
        $this->dispatch('/file/setLocation/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(409);
    }

    public function testCreateCanBeAccessed()
    {
        $LocationFilter = $this->getMock('File\Filter\LocationFilter', array('setData', 'isValid'), array(), '', null);
        $LocationFilter->expects($this->any())->method('setData')->with($this->stubLocation);
        $LocationFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));
        $this->serviceManager->setService('File\Filter\LocationFilter', $LocationFilter);

        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->stubLocation);
        $this->dispatch('/file/setLocation/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(200);
    }

}