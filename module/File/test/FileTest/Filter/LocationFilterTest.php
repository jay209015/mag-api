<?php
/**
 * Created by PhpStorm.
 * User: obaccay
 * Date: 3/28/2015
 * Time: 4:54 PM
 */
namespace FileTest\Filter;

use File\Filter\LocationFilter;

class LocationFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $LocationFilter = new LocationFilter();
        $this->assertInstanceOf('File\Filter\LocationFilter', $LocationFilter);
    }

    public function testLocationFilterCanValidateData()
    {
        $data = array(
            "file_id" => 1,
            "server" => "S3",
            "path" => "8ce209c8ccaed1f2ba66a3f0fc5ef725.jpg"
        );

        $LocationFilter = new LocationFilter();
        $LocationFilter->setData($data);

        $this->assertFalse($LocationFilter->isValid());

    }

}