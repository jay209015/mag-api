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

class StatusFilter extends InputFilter
{
    public function __construct()
    {

        // status
        $this->add(array(
            'name' => 'status',
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
                            NotEmpty::IS_EMPTY => 'Status is required.',
                        ),
                    ),
                )
            ),
        ));
    }
}