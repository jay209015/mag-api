<?php
/**
 * Created by PhpStorm.
 * User: obaccay
 * Date: 3/28/2015
 * Time: 4:54 PM
 */
namespace FileTest\Filter;

use File\Filter\MockupFilter;

class MockupFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $MockupFilter = new MockupFilter();
        $this->assertInstanceOf('File\Filter\MockupFilter', $MockupFilter);
    }

    public function testThumbnailFilterCanValidateData()
    {
        $data = array(
            "file_id" => 1,
            "page" => 1,
            "width" => "abc",
            "path" => "CMYK.jpg"
        );

        $MockupFilter = new MockupFilter();
        $MockupFilter->setData($data);

        $this->assertFalse($MockupFilter->isValid());

    }

}