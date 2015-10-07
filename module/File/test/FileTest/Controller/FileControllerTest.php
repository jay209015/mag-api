<?php
namespace FileTest\FileTest\Controller;

use FileTest\Bootstrap;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\View\Model\JsonModel;

class FileControllerTest extends AbstractHttpControllerTestCase
{

    protected function setUp()
    {
        putenv('APPLICATION_ENV=local');
        $this->setApplicationConfig(Bootstrap::getConfig());
        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);
    }

    protected function createFilesSetUp()
    {
        $this->file_code = '6a6ee1383498ce73cb1b4dc2c0f26477c9acc495';
        $this->file_id = 123;
        $this->parent_file_id = 0;
        $this->thumbnail = array(
            "page" => 1,
            "width" => 200,
            "path" => "8ce209c8cc-1426736564105-assassins_creed_comet_200_1.jpg"
        );
        $this->mockup = array(

        );
        $this->ordered = array(

        );

        $this->location = array(
            'file_id'    => 1,
            'file_code'  => "abcdefg",
            'server'     => "S3",
            'path'       => "abcdefg.jpg",
            'old_path'   => NULL,
            'date_added' => date("Y-m-d H:i:s"),
            'migrated'   => 0,
        );
        $this->locations = array(
            $this->location
        );

        $this->page = array(
            'file_id'    => 1,
            'file_code'  => "abcdefg",
            'page'       => 1,
            'width'      => 120,
            'height'     => 120,
            'colorspace' => "CMYK"
        );
        $this->pages = array(
            $this->page
        );

        $this->file = array(
            'name' => "test.jpg",
            'type'=> "image/jpeg",
            'size' => 1111,
            'extension' => "jpg",
            'website_code' => "PR",
            'origin' => "SITE",
            'actual_filename' => "abcdefg-test.jpg",
            'locations' => $this->locations,
            'pages' => $this->pages
        );

        $this->request = array(
            "vid" => "9349787",
            "cid" => "645594",
            "files" => array($this->file),
            "thumbnail" => array(
                $this->thumbnail
            )
        );

        $this->validation = new JsonModel(array('valid' => true, 'errors' => ''));

        $Files = $this->getMock('File\Model\Files', array('exchangeArray', 'generateFileCode', 'setParentFileId',
            'getFile', 'getLocations', 'getPages'), array(), '', null);
        $Files->expects($this->any())->method('exchangeArray');
        $Files->expects($this->any())->method('generateFileCode')->will($this->returnValue($this->file_code));
        $Files->expects($this->any())->method('setParentFileId');
        $Files->expects($this->any())->method('getFile')->will($this->returnValue($this->file));
        $Files->expects($this->any())->method('getLocations')->will($this->returnValue($this->locations));
        $Files->expects($this->any())->method('getPages')->will($this->returnValue($this->pages));

        $FileTable = $this->getMock('File\Model\FileTable', array('insert'), array(), '', null);
        $FileTable->expects($this->any())->method('insert')->will($this->returnValue($this->file_id));

        $FileFilter = $this->getMock('File\Filter\FileFilter', array('setData', 'isValid'), array(), '', null);
        $FileFilter->expects($this->any())->method('setData')->with($this->file);
        $FileFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));

        $FileService = $this->getMock('File\Service\FileService', array('getFileDocument'), array(), '', null);
        $FileService->expects($this->any())->method('getFileDocument')->will($this->returnValue($this->request));

        $LocationTable = $this->getMock('File\Model\LocationTable', array('insert'), array(), '', null);
        $LocationTable->expects($this->any())->method('insert');

        $LocationFilter = $this->getMock('File\Filter\LocationFilter', array('setData', 'isValid'), array(), '', null);
        $LocationFilter->expects($this->any())->method('setData')->with($this->location);
        $LocationFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));

        $PageTable = $this->getMock('File\Model\PageTable', array('insert'), array(), '', null);
        $PageTable->expects($this->any())->method('insert');

        $PageFilter = $this->getMock('File\Filter\PageFilter', array('setData', 'isValid'), array(), '', null);
        $PageFilter->expects($this->any())->method('setData')->with($this->page);
        $PageFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));

        $Thumbnail = $this->getMock('File\Model\Thumbnail', array('exchangeArray'), array(), '', null);
        $Thumbnail->expects($this->any())->method('exchangeArray');

        $ThumbnailTable = $this->getMock('File\Model\ThumbnailTable', array('saveThumbnail'), array(), '', null);
        $ThumbnailTable->expects($this->any())->method('saveThumbnail');

        $ThumbnailFilter = $this->getMock('File\Filter\ThumbnailFilter', array('setData', 'isValid'), array(), '', null);
        $ThumbnailFilter->expects($this->any())->method('setData')->with($this->thumbnail);
        $ThumbnailFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));

        $Ordered = $this->getMock('File\Model\Ordered', array('exchangeArray'), array(), '', null);
        $Ordered->expects($this->any())->method('exchangeArray');

        $OrderedTable = $this->getMock('File\Model\OrderedTable', array('saveOrdered'), array(), '', null);
        $OrderedTable->expects($this->any())->method('saveOrdered');

        $OrderedFilter = $this->getMock('File\Filter\OrderedFilter', array('setData', 'isValid'), array(), '', null);
        $OrderedFilter->expects($this->any())->method('setData')->with($this->ordered);
        $OrderedFilter->expects($this->any())->method('isValid')->will($this->returnValue(true));

        $this->serviceManager->setService('Files', $Files);
        $this->serviceManager->setService('FileTable', $FileTable);
        $this->serviceManager->setService('FileFilter', $FileFilter);
        $this->serviceManager->setService('FileService', $FileService);
        $this->serviceManager->setService('File\Model\LocationTable', $LocationTable);
        $this->serviceManager->setService('LocationFilter', $LocationFilter);
        $this->serviceManager->setService('PageTable', $PageTable);
        $this->serviceManager->setService('File\Filter\PageFilter', $PageFilter);
        $this->serviceManager->setService('Thumbnail', $Thumbnail);
        $this->serviceManager->setService('ThumbnailTable', $ThumbnailTable);
        $this->serviceManager->setService('File\Filter\ThumbnailFilter', $ThumbnailFilter);
        $this->serviceManager->setService('Ordered', $Ordered);
        $this->serviceManager->setService('OrderedTable', $OrderedTable);
        $this->serviceManager->setService('File\Filter\OrderedFilter', $OrderedFilter);
    }

    public function testCreateCanBeAccessed()
    {
        $this->createFilesSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode($this->request));

        $this->dispatch('/file', 'POST');
        $this->assertResponseStatusCode(200);
    }

    public function testCreateReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->createFilesSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode($this->request));

        $this->dispatch('/file', 'POST');
        $this->assertResponseStatusCode(401);
    }

    public function testCreateReturns409StatusCodeIfFilesObjectIsMissing()
    {
        $this->createFilesSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array()));

        $this->dispatch('/file', 'POST');
        $this->assertResponseStatusCode(409);

    }

    public function testCreateReturns409StatusCodeIfFilesObjectHasMissingProperties()
    {
        $this->createFilesSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array(
            "files" => array()
        )));

        $this->dispatch('/file', 'POST');
        $this->assertResponseStatusCode(409);
    }

    protected function getFilesSetUp()
    {
        $this->data = array(
            'customer_id' => 100,
        );

        $this->file_data = array(
            "file_id" => 1,
        );

        $this->document_data = array(
            "files" => array(
                "name" => "test.pdf"
            ),
        );
        $fileTable = $this->getMock('File\Model\FileTable', array('getFiles'), array(), '', null);
        $fileTable->expects($this->any())
            ->method('getFiles')
            ->will($this->returnValue(array()));
        $this->serviceManager->setService('FileTable', $fileTable);

        $fileService = $this->getMock('File\Service\FileServiceSlave', array('getFiles', 'getFileDocument'), array(), '', null);

        $fileService->expects($this->any())
            ->method('getFiles')
            ->will($this->returnValue(array($this->file_data)));

        $fileService->expects($this->any())
            ->method('getFileDocument')
            ->will($this->returnValue(array($this->document_data)));

        $this->serviceManager->setService('FileServiceSlave', $fileService);
    }

    public function testGetFilesCanBeAccessed()
    {
        $this->getFilesSetUp();
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->dispatch('/file/getFiles', 'GET', $this->data);
        $this->assertResponseStatusCode(200);
    }

    public function testGetFilesReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->getFilesSetUp();
        $this->dispatch('/file/getFiles', 'GET', $this->data);
        $this->assertResponseStatusCode(401);
    }


} 