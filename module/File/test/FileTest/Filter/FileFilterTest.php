<?php
/**
 * Created by PhpStorm.
 * User: obaccay
 * Date: 3/28/2015
 * Time: 4:54 PM
 */
namespace FileTest\Filter;

use File\Filter\FileFilter;

class FileFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $FileFilter = new FileFilter();
        $this->assertInstanceOf('File\Filter\FileFilter', $FileFilter);
    }

    public function testFileFilterCanValidateData()
    {
        $data = array(
            "type" => "image/jpeg",
            "size" => 522763,
            "extension" => "jpg",
            "website_code" => "PR",
            "origin" => "SITE",
            "actual_filename" => "8ce209c8ccaed1f2ba66a3f0fc5ef725.jpg",
            "file_code" => "8ce209c8ccaed1f2ba66a3f0fc5ef725",
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

        $FileFilter = new FileFilter();
        $FileFilter->setData($data);

        $this->assertFalse($FileFilter->isValid());

    }

}