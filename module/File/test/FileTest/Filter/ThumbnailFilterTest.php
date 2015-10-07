<?php
/**
 * Created by PhpStorm.
 * User: obaccay
 * Date: 3/28/2015
 * Time: 4:54 PM
 */
namespace FileTest\Filter;

use File\Filter\ThumbnailFilter;

class ThumbnailFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $ThumbnailFilter = new ThumbnailFilter();
        $this->assertInstanceOf('File\Filter\ThumbnailFilter', $ThumbnailFilter);
    }

    public function testThumbnailFilterCanValidateData()
    {
        $data = array(
            "file_id" => 1,
            "page" => 1,
            "width" => "abc",
            "path" => "CMYK.jpg"
        );

        $ThumbnailFilter = new ThumbnailFilter();
        $ThumbnailFilter->setData($data);

        $this->assertFalse($ThumbnailFilter->isValid());

    }

}