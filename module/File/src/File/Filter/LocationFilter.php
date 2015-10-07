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

class LocationFilter extends InputFilter
{
    public function __construct()
    {

        // file_id
        $this->add(array(
            'name' => 'file_id',
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

        // server
        $this->add(array(
            'name' => 'server',
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
                            NotEmpty::IS_EMPTY => 'Server is required.',
                        ),
                    ),
                )
            ),
        ));

        // path
        $this->add(array(
            'name' => 'path',
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
                            NotEmpty::IS_EMPTY => 'Path is required.',
                        ),
                    ),
                )
            ),
        ));

        // date_added
        $this->add(array(
            'name' => 'date_added',
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
                            NotEmpty::IS_EMPTY => 'Date Added is required.',
                        ),
                    ),
                )
            ),
        ));

        // file_code
        $this->add(array(
            'name' => 'file_code',
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
                            NotEmpty::IS_EMPTY => 'file_code is required.',
                        ),
                    ),
                    'format' => 'Y-m-d'
                )
            ),
        ));

    }
}