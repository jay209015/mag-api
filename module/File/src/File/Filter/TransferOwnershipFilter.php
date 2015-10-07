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

class TransferOwnershipFilter extends InputFilter
{
    public function __construct()
    {

        // vid
        $this->add(array(
            'name' => 'vid',
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
                            NotEmpty::IS_EMPTY => 'vid is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid Visitor ID.',
                            Digits::INVALID => 'Invalid Visitor ID.',
                        ),
                    ),
                ),
            ),
        ));

        // cid
        $this->add(array(
            'name' => 'cid',
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
                            NotEmpty::IS_EMPTY => 'cid is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid Customer ID.',
                            Digits::INVALID => 'Invalid Customer ID.',
                        ),
                    ),
                ),
            ),
        ));
    }
}