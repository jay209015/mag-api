<?php
/**
 * Created by PhpStorm.
 * User: prince
 * Date: 3/15/15
 * Time: 2:18 PM
 */

namespace FileTest\FileTest\Controller;


use FileTest\Bootstrap;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\View\Model\JsonModel;

class LocationByNameControllerTest extends AbstractHttpControllerTestCase
{
    protected $stubLocation;
    protected $serviceManager;
    protected $stubFileInfo;

    public function setUp()
    {
        putenv('APPLICATION_ENV=local');
        $this->setApplicationConfig(Bootstrap::getConfig());
        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);

        $this->stubLocation = json_encode(
            array(
                "location" => array(
                    "server" => "S3",
                    "path" => "888abf-1408504715-5095IMDEV3.pdf",
                    "date_added" => "2012-02-13T10:17:55.000Z"
                )
            )
        );
        $this->stubFileInfo = array(
            'file_id'         => 123,
            'file_group_id'   => 1,
            'origin'          => 'PRINT',
            'vid'             => 0,
            'cid'             => 1234,
            'website_code'    => 'UP',
            'name'            => 'print2.pdf',
            'actual_filename' => '5107IMDEV3.pdf',
            'extension'       => 'pdf',
            'content_type'    => 'application/pdf',
            'size'            => '3966640',
            'created'         => '2012-02-18 07:34:03',
            'deleted'         => '0',
            'file_code'       => 'ecb65266627e8addb622025c923f10e5307ef81a',
            'from_migration'  => '2',
        );

        $locationModel = $this->getMock('File\Model\Location', array('exchangeArray'), array(), '', null);
        $locationModel->expects($this->any())
            ->method('exchangeArray');
        $this->serviceManager->setService('Location', $locationModel);

        $validator = $this->getMock('File\Validation\LocationValidation', array('validateData'), array(), '', null);
        $validator->expects($this->any())
            ->method('validateData')
            ->will($this->returnValue(new JsonModel(array('valid' => true, 'errors' => ''))));
        $this->serviceManager->setService('LocationValidation', $validator);

        $fileTable = $this->getMock('File\Model\FileTable', array('getFileByActualFileName'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFileByActualFileName')
            ->will($this->returnValue(new JsonModel($this->stubFileInfo)));
        $this->serviceManager->setService('FileTable', $fileTable);

        $locationTable = $this->getMock('File\Model\LocationTable', array('saveLocation', 'getLocation'), array(), '', null);
        $locationTable->expects($this->any())
            ->method('saveLocation')
            ->will($this->returnValue(3));

        $this->serviceManager->setService('LocationTable', $locationTable);

    }

    public function testCreateReturns401StatusCodeIfClientIsNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/file/setLocationByName/5107IMDEV3.pdf', 'POST');
        $this->assertResponseStatusCode(401);
    }

    public function testCreateReturns409StatusCodeIfLocationObjectIsMissing()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/file/setLocationByName/5107IMDEV3.pdf', 'POST');
        $this->assertResponseStatusCode(409);
    }

    public function testCreateReturns409StatusCodeIfLocationObjectHasMissingProperties()
    {
        $validator = $this->getMock('File\Validation\LocationValidation', array('validateData'), array(), '', null);
        $validator->expects($this->any())
            ->method('validateData')
            ->will($this->returnValue(new JsonModel(array('valid' => false, 'errors' => ''))));
        $this->serviceManager->setService('LocationValidation', $validator);

        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array("location" => array("server" => "S3"))));
        $this->dispatch('/file/setLocationByName/5107IMDEV3.pdf', 'POST');
        $this->assertResponseStatusCode(409);
    }

    public function testCreateReturns404StatusCodeIfFileIsNotFound()
    {
        $MockFileTable = $this->getMock('File\Model\FileTable', array('getFileByActualFileName'), array(), '', null);
        $MockFileTable->expects($this->any())
            ->method('getFileByActualFileName')
            ->will($this->returnValue(null));
        $this->serviceManager->setService('FileTable', $MockFileTable);

        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->stubLocation);
        $this->dispatch('/file/setLocationByName/5107IMDEV3.pdf', 'POST');
        $this->assertResponseStatusCode(404);
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
        $this->dispatch('/file/setLocationByName/5107IMDEV3.pdf', 'POST');

        $this->assertResponseStatusCode(200);
    }
} 