<?php
namespace FileTest\FileTest\Controller;

use FileTest\Bootstrap;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\View\Model\JsonModel;

class MockupControllerTest extends AbstractHttpControllerTestCase
{

    protected function setUp()
    {
        putenv('APPLICATION_ENV=local');
        $this->setApplicationConfig(Bootstrap::getConfig());
        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);
    }

    protected function createMockupSetUp()
    {
        $this->file_code = '6a6ee1383498ce73cb1b4dc2c0f26477c9acc495';
        $this->file_id = 123;
        $this->parent_file_id = 0;
        $this->mockup = array(
            "page" => 1,
            "width" => 1440,
            "path" => "888abf-1408504715-5095IMDEV3_1440_1.jpg"
        );
        $this->request = array(
            "mockup" => array(
                $this->mockup
            )
        );

        $this->validation = new JsonModel(array('valid' => true, 'errors' => ''));

        $FileTable = $this->getMock('File\Model\FileTableSlave', array('getFileId'), array(), '', null);
        $FileTable->expects($this->any())->method('getFileId')->will($this->returnValue($this->file_id));

        $FileService = $this->getMock('File\Service\FileService', array('getFileDocument'), array(), '', null);
        $FileService->expects($this->any())->method('getFileDocument')->will($this->returnValue($this->request));

        $Mockup = $this->getMock('File\Model\Mockup', array('exchangeArray'), array(), '', null);
        $Mockup->expects($this->any())->method('exchangeArray');

        $MockupFilter = $this->getMock('File\Filter\MockupFilter', array('setData', 'isValid'), array(), '', null);
        $MockupFilter->expects($this->any())->method('setData')->with($this->mockup);
        $MockupFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));
        
        $MockupTable = $this->getMock('File\Model\MockupTable', array('saveMockup', 'getMockup'), array(), '', null);
        $MockupTable->expects($this->any())->method('saveMockup');

        $this->serviceManager->setService('FileTableSlave', $FileTable);
        $this->serviceManager->setService('FileService', $FileService);
        $this->serviceManager->setService('Mockup', $Mockup);
        $this->serviceManager->setService('MockupTable', $MockupTable);
        $this->serviceManager->setService('File\Filter\MockupFilter', $MockupFilter);
    }

    public function testCreateMockupCanBeAccessed()
    {
        $this->createMockupSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode($this->request));

        $this->dispatch('/file/setMockup', 'POST');
        $this->assertResponseStatusCode(200);
    }

    public function testCreateMockupReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->createMockupSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode($this->request));

        $this->dispatch('/file/setMockup', 'POST');
        $this->assertResponseStatusCode(401);
    }

    public function testCreateMockupReturns409StatusCodeIfMockupObjectIsMissing()
    {
        $this->createMockupSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array()));

        $this->dispatch('/file/setMockup', 'POST');
        $this->assertResponseStatusCode(409);

    }

    public function testCreateMockupReturns409StatusCodeIfMockupObjectHasMissingProperties()
    {
        $this->createMockupSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array(
            "mockup" => array()
        )));

        $this->dispatch('/file/setMockup', 'POST');
        $this->assertResponseStatusCode(409);
    }


} 