<?php
/**
 * Created by PhpStorm.
 * User: obaccay
 * Date: 3/28/2015
 * Time: 4:54 PM
 */
namespace FileTest\Filter;

use File\Filter\OrderedFilter;

class OrderedFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $OrderedFilter = new OrderedFilter();
        $this->assertInstanceOf('File\Filter\OrderedFilter', $OrderedFilter);
    }

    public function testOrderedFilterCanValidateData()
    {
        $data = array(
            "file_id" => 1,
            "job_order_id" => 1,
            "job_item_id" => "abc",
            "order_date" => "2015-01-01T02:02:02.000Z"
        );

        $OrderedFilter = new OrderedFilter();
        $OrderedFilter->setData($data);

        $this->assertFalse($OrderedFilter->isValid());

    }

}