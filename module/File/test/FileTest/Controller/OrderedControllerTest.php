<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 3:19 AM
 */

namespace FileTest\FileTest\Controller;


use FileTest\Bootstrap;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\View\Model\JsonModel;

class OrderedControllerTest extends AbstractHttpControllerTestCase
{
    protected $serviceManager;
    protected $stubRequestContent;
    protected $stubFileData;

    protected function setUp()
    {
        putenv('APPLICATION_ENV=local');
        $this->setApplicationConfig(Bootstrap::getConfig());
        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);

        $this->stubRequestContent = json_encode(array(
            "ordered" => array(
                "job_order_id" =>3,
                "job_item_id" => 3,
                "order_date" => "2008-08-12 00:00:00"
            )
        ));

        $this->stubFileData = array(
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
        );

        $OrderedModel = $this->getMock('File\Model\Ordered', array('exchangeArray'), array(), '', null);
        $OrderedModel->expects($this->any())
            ->method('exchangeArray');
        $this->serviceManager->setService('Ordered', $OrderedModel);

        $fileTable = $this->getMock('File\Model\FileTable', array('getFileId'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFileId')
            ->will($this->returnValue(123));
        $this->serviceManager->setService('FileTable', $fileTable);

        $validator = $this->getMock('File\Validation\OrderedValidation', array('validate', 'shouldHaveJobOrderId', 'shouldHaveJobItemId', 'shouldHaveOrderDate'), array(), '', null);
        $validator->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(new JsonModel(array('valid' => true, 'errors' => ''))));
        $this->serviceManager->setService('OrderedValidation', $validator);

        $OrderedTable = $this->getMock('File\Model\OrderedTable', array('saveOrdered'), array(), '', null);
        $OrderedTable->expects($this->any())
            ->method('saveOrdered');
        $this->serviceManager->setService('OrderedTable', $OrderedTable);

        $fileModel = $this->getMock('File\Model\File', array('exchangeArray'), array(), '', null);
        $fileModel->expects($this->any())
            ->method('exchangeArray');
        $this->serviceManager->setService('File', $fileModel);

        $fileService = $this->getMock('File\Service\FileService', array('getFileInfo'), array(), '', null);
        $fileService->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(new JsonModel($this->stubFileData)));
        $this->serviceManager->setService('FileService', $fileService);
    }

    public function testCreateCanBeAccessed()
    {
        $OrderedTable = $this->getMock('File\Model\OrderedTable', array('saveOrdered'), array(), '', null);
        $OrderedTable->expects($this->any())
            ->method('saveOrdered')
            ->will($this->returnValue(1));
        $this->serviceManager->setService('OrderedTable', $OrderedTable);

        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->stubRequestContent);

        $this->dispatch('/file/setAsOrdered/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(200);
    }

    public function testCreateWillReturn304StatusCodeIfOrderedDataIsAlreadyExisting()
    {
        $OrderedTable = $this->getMock('File\Model\OrderedTable', array('saveOrdered'), array(), '', null);
        $OrderedTable->expects($this->any())
            ->method('saveOrdered')
            ->will($this->returnValue(false));
        $this->serviceManager->setService('OrderedTable', $OrderedTable);

        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->stubRequestContent);

        $this->dispatch('/file/setAsOrdered/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(304);
    }

    public function testCreateReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->stubRequestContent);

        $this->dispatch('/file/setAsOrdered/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(401);
    }

    public function testCreateReturns409StatusCodeIfOrderedObjectIsMissing()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array()));

        $this->dispatch('/file/setAsOrdered/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(409);

    }

    public function testCreateReturns409StatusCodeIfOrderedObjectHasMissingPropertiesIsMissing()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array(
            "ordered" => array()
        )));

        $this->dispatch('/file/setAsOrdered/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(409);
    }

    public function testCreateReturns409StatusCodeIfFileIdDoesNotExist()
    {
        $fileService = $this->getMock('File\Service\FileService', array('getFileInfo'), array(), '', null);
        $fileService->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(null));
        $this->serviceManager->setService('FileService', $fileService);

        $fileTable = $this->getMock('File\Model\FileTable', array('getFileInfo'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFileInfo')
            ->will($this->returnValue(null));
        $this->serviceManager->setService('FileTable', $fileTable);

        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->stubRequestContent);

        $this->dispatch('/file/setAsOrdered/ecb65266627e8addb622025c923f10e5307ef81a', 'POST');
        $this->assertResponseStatusCode(409);
    }
} 