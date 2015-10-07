<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/5/15
 * Time: 11:02 PM
 */

namespace FileTest\FileTest\Controller;

use Zend\View\Model\JsonModel;
use FileTest\Bootstrap;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class TransferOwnershipControllerTest extends AbstractHttpControllerTestCase
{
    protected $serviceManager;

    protected function setUp()
    {
        putenv('APPLICATION_ENV=local');
        $this->setApplicationConfig(Bootstrap::getConfig());


        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);

        $this->data = json_encode(array(
            'vid' => '111',
            'cid' => '222',
        ));

        $fileModel = $this->getMock('File\Model\File', array('exchangeArray'), array(), '', null);
        $fileModel->expects($this->any())
            ->method('exchangeArray');
        $this->serviceManager->setService('File', $fileModel);

        $fileTable = $this->getMock('File\Model\FileTable', array('transferOwnership'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('transferOwnership')
            ->will($this->returnValue(1));
        $this->serviceManager->setService('FileTable', $fileTable);
    }

    public function testReplaceCanBeAccessed()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->data);

        $this->dispatch('/file/transferOwnership', 'PUT');
        $this->assertResponseStatusCode(200);

    }

    public function testReplaceReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->data);

        $this->dispatch('/file/transferOwnership', 'PUT');
        $this->assertResponseStatusCode(401);
    }

    public function testCanTransferOwnership()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent($this->data);

        $expected = (object) (array("message" => "Success"));

        $this->dispatch('/file/transferOwnership', 'PUT');
        $response = $this->getResponse();
        $actual = $response->getContent();
        $this->assertEquals($expected, json_decode($actual));
    }

} 