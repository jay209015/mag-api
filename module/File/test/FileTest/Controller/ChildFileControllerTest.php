<?php
/**
 * author: emman@uprinting.com
 */
namespace FileTest\FileTest\Controller;

use FileTest\Bootstrap;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\View\Model\JsonModel;

class ChildFileControllerTest extends AbstractHttpControllerTestCase
{

    protected function setUp()
    {
        putenv('APPLICATION_ENV=local');
        $this->setApplicationConfig(Bootstrap::getConfig());
        parent::setUp();

        $this->serviceManager = $this->getApplicationServiceLocator();
        $this->serviceManager->setAllowOverride(true);
 
        $this->file_code = '7777777777777777EMMMMANNNNNN777777888';
        $this->file_id = 123;
        $this->parent_file_id = 100;
        $this->thumbnail = array(
            "page" => 1,
            "width" => 200,
            "path" => "8ce209c8cc-1426736564105-assassins_creed_comet_200_1.jpg"
        );
        $this->request = array(
            "vid" => "9349787",
            "cid" => "645594",
            "files" => array(
                array(
                    "name" => "assassins_creed_comet.jpg",
                    "type" => "image/jpeg",
                    "size" => 522763,
                    "extension" => "jpg",
                    "website_code" => "PR",
                    "origin" => "SITE",
                    "actual_filename" => "8ce209c8ccaed1f2ba66a3f0fc5ef725.jpg",
                    "pages" => array(
                        array(
                            "page" => "1",
                            "width" => "1680",
                            "height" => "1050",
                            "colorspace" => "RGB"
                        )
                    ),
                    "location" => array(
                        array(
                            "server" => "S3",
                            "path" => "8ce209c8ccaed1f2ba66a3f0fc5ef725.jpg"
                        ),
                    )
                )
            ),
            "thumbnail" => array(
                $this->thumbnail
            )
        );
    
        $file = $this->request['files'][0];
        $this->file = array(
            'parent_file_id' => $this->parent_file_id,
            'file_code' => $this->file_code,
            'cid' => $this->request['cid'],
            'vid' => $this->request['vid'],
            'name' => $file['name'],
            'actual_filename' => $file['actual_filename'],
            'content_type'=> $file['type'],
            'size' => $file['size'],
            'extension' => $file['extension'],        
            'website_code' => $file['website_code'],
            'origin' => $file['origin']
        );

        $location = $file['location'][0];
        $this->location = array(
            'file_id'    => $this->file_id,
            'file_code'  => $this->file_code,
            'server'     => $location['server'],
            'path'       => $location['path'],
            'old_path'   => NULL,
            'date_added' => date("Y-m-d H:i:s"),
            'migrated'   => 0,
        );
        $this->locations = array(
            $this->location
        );

        $page = $file['pages'][0];
        $this->page = array(
            'file_id'    => $this->file_id,
            'file_code'  => $this->file_code,
            'page'       => $page['page'],
            'width'      => $page['width'],
            'height'     => $page['height'],
            'colorspace' => $page['colorspace']
        );
        $this->pages = array(
            $this->page
        );
        $this->mockup = array(

        );
        $this->ordered = array(

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

        $FileTable = $this->getMock('File\Model\FileTable', array('insert', 'getFileId'), array(), '', null);
        $FileTable->expects($this->any())->method('insert')->will($this->returnValue($this->file_id));
        $FileTable->expects($this->any())->method('getFileId')->will($this->returnValue($this->parent_file_id));

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
        $this->serviceManager->setService('File\Filter\FileFilter', $FileFilter);
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
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode($this->request));

        $this->dispatch('/file/setFiles/6a6ee1383498ce73cb1b4dc2c0f26477c9acc495', 'POST');
        $this->assertResponseStatusCode(200);

    }

    public function testCreateReturns401StatusCodeIfClientNotAuthenticated()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode($this->request));

        $this->dispatch('/file/setFiles/6a6ee1383498ce73cb1b4dc2c0f26477c9acc495', 'POST');
        $this->assertResponseStatusCode(401);
    }

    public function testCreateReturns409StatusCodeIfFilesObjectIsMissing()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array()));

        $this->dispatch('/file/setFiles/6a6ee1383498ce73cb1b4dc2c0f26477c9acc495', 'POST');
        $this->assertResponseStatusCode(409);

    }

    public function testCreateReturns409StatusCodeIfFilesObjectHasMissingProperties()
    {
        $this->getRequest()->getHeaders()->addHeaderLine('Authorization', 'Basic cHJldmlld0FwaUNsaWVudDpwcmV2aWV3QXBpQ2xpZW50');
        $this->getRequest()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->getRequest()->setContent(json_encode(array(
            "files" => array()
        )));

        $this->dispatch('/file/setFiles/6a6ee1383498ce73cb1b4dc2c0f26477c9acc495', 'POST');
        $this->assertResponseStatusCode(409);
    }
} 