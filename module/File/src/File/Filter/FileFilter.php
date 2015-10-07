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

class FileFilter extends InputFilter
{
    public function __construct()
    {

        // name
        $this->add(array(
            'name' => 'name',
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
                            NotEmpty::IS_EMPTY => 'Name is required.',
                        ),
                    ),
                )
            ),
        ));

        // type
        $this->add(array(
            'name' => 'type',
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
                            NotEmpty::IS_EMPTY => 'Type is required.',
                        ),
                    ),
                )
            ),
        ));

        // size
        $this->add(array(
            'name' => 'size',
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
                            NotEmpty::IS_EMPTY => 'Size is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid size',
                            Digits::INVALID => 'Invalid size',
                        ),
                    ),
                ),
            ),
        ));

        // extension
        $this->add(array(
            'name' => 'extension',
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
                            NotEmpty::IS_EMPTY => 'Extension is required.',
                        ),
                    ),
                )
            ),
        ));

        // origin
        $this->add(array(
            'name' => 'origin',
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
                            NotEmpty::IS_EMPTY => 'Origin is required.',
                        ),
                    ),
                )
            ),
        ));

        // location
        $this->add(array(
            'name' => 'location',
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
                            NotEmpty::IS_EMPTY => 'Location is required.',
                        ),
                    ),
                )
            ),
        ));
    }
}