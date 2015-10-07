<?php
/**
 * Created by PhpStorm.
 * User: obaccay
 * Date: 3/28/2015
 * Time: 4:54 PM
 */
namespace FileTest\Filter;

use File\Filter\PageFilter;

class PageFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $PageFilter = new PageFilter();
        $this->assertInstanceOf('File\Filter\PageFilter', $PageFilter);
    }

    public function testPageFilterCanValidateData()
    {
        $data = array(
            "file_id" => 1,
            "page" => 1,
            "width" => "abc",
            "height" => "abc",
            "colorspace" => "CMYK"
        );

        $PageFilter = new PageFilter();
        $PageFilter->setData($data);

        $this->assertFalse($PageFilter->isValid());

    }

}