<?php
/**
 * Created by PhpStorm.
 * User: obaccay
 * Date: 3/28/2015
 * Time: 10:19 AM
 */

namespace File\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Validator\NotEmpty;
use Zend\Validator\Date;

class OrderedFilter extends InputFilter
{
    public function __construct()
    {

        // file_id
        $this->add(array(
            'name' => 'file_id',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'file_id is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid File ID.',
                            Digits::INVALID => 'Invalid File ID.',
                        ),
                    ),
                ),
            ),
        ));

        // job_order_id
        $this->add(array(
            'name' => 'job_order_id',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Job Order ID is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid Job Order ID',
                            Digits::INVALID => 'Invalid Job Order ID',
                        ),
                    ),
                ),
            ),
        ));

        // job_item_id
        $this->add(array(
            'name' => 'job_item_id',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Job Item ID is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid Job Item ID',
                            Digits::INVALID => 'Invalid Job Item ID',
                        ),
                    ),
                ),
            ),
        ));

        // order_date
        $this->add(array(
            'name' => 'order_date',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'Order Date is required.',
                        ),
                    ),
                )
            ),
        ));

        // file_code
        $this->add(array(
            'name' => 'file_code',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            NotEmpty::IS_EMPTY => 'file_code is required.',
                        ),
                    ),
                )
            ),
        ));

    }
}