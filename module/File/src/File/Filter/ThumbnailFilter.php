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

class ThumbnailFilter extends InputFilter
{
    public function __construct()
    {

        // page
        $this->add(array(
            'name' => 'page',
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
                            NotEmpty::IS_EMPTY => 'Page is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid page',
                            Digits::INVALID => 'Invalid page',
                        ),
                    ),
                ),
            ),
        ));

        // width
        $this->add(array(
            'name' => 'width',
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
                            NotEmpty::IS_EMPTY => 'Width is required.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            Digits::NOT_DIGITS => 'Invalid width',
                            Digits::INVALID => 'Invalid width',
                        ),
                    ),
                ),
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

    }
}