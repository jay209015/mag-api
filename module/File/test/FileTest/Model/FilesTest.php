<?php
/**
 * author: emman@uprinting.com
 */

namespace FileTest\Model;


use File\Model\Files;

class FilesTest extends \PHPUnit_Framework_TestCase
{
    
    protected $data = array('cid' => 8888, 'vid' => 1223);
    protected $file  =  array(
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
    );

    public function testFilesInitialState()
    {
        $Files = new Files();

        $this->assertNull($Files->cid);
        $this->assertNull($Files->vid);
        $this->assertNull($Files->file_code);
        $this->assertNull($Files->parent_file_id);
        $this->assertNull($Files->name);
        $this->assertNull($Files->actual_filename);
        $this->assertNull($Files->type);
        $this->assertNull($Files->size);
        $this->assertNull($Files->extension);
        $this->assertNull($Files->website_code);
        $this->assertNull($Files->origin);
        $this->assertNull($Files->location);
        $this->assertNull($Files->pages);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $Files = new Files();

        $Files->exchangeArray($this->file, $this->data);

        $this->assertSame($this->data['cid'], $Files->cid);
        $this->assertSame($this->data['vid'], $Files->vid);
        $this->assertSame($this->file['name'], $Files->name);
        $this->assertSame($this->file['type'], $Files->type);
        $this->assertSame($this->file['size'], $Files->size);
        $this->assertSame($this->file['website_code'], $Files->website_code);
        $this->assertSame($this->file['origin'], $Files->origin);
        $this->assertSame($this->file['actual_filename'], $Files->actual_filename);
        $this->assertSame($this->file['pages'], $Files->pages);
        $this->assertSame($this->file['location'], $Files->location);
    }

    public function testExchangeArraySetsPropertiesToDefaultIfKeysAreNotPresent()
    {
        $Files = new Files();
        $Files->exchangeArray(array(), array());

        $this->assertSame(0, $Files->cid);
        $this->assertSame(0, $Files->vid);
        $this->assertNotNull($Files->file_code);
        $this->assertSame(0, $Files->parent_file_id);
        $this->assertNull($Files->name);
        $this->assertNull($Files->actual_filename);
        $this->assertNull($Files->type);
        $this->assertNull($Files->size);
        $this->assertNull($Files->extension);
        $this->assertNull($Files->website_code);
        $this->assertNull($Files->origin);
        $this->assertSame(array(), $Files->location);
        $this->assertSame(array(), $Files->pages);
    }

    public function testFileCodeIsAlwaysGenerated()
    {
        $Files = new Files();
        $this->assertNotNull($Files->generateFileCode());
    }

    public function testParentFileIdIsBeingSet()
    {
        $Files = new Files();
        $parent_file_id = 123453;
        $Files->setParentFileId($parent_file_id);
        $this->assertSame($parent_file_id, $Files->parent_file_id);        
    }

} 