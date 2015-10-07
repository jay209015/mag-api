<?php
return array(
    'router' => array(
        'routes' => array(
            'file' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file',
                    'defaults' => array(
                        'controller' => 'File\Controller\File'
                    ),
                ),
            ),
            'setFiles' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/setFiles[/:file_code]',
                    'constraints' => array(
                        'file_code' => '[0-9a-f]+',
                    ),
                    'defaults' => array(
                        'controller' => 'File\Controller\ChildFile'
                    ),
                ),
            ),            
            'transferOwnership' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/transferOwnership',
                    'defaults' => array(
                        'controller' => 'File\Controller\TransferOwnership'
                    ),
                ),
            ),
            'getLocation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/getLocation',
                    'defaults' => array(
                        'controller' => 'File\Controller\Location'
                    ),
                ),
            ),
            'setAsOrdered' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/setAsOrdered/:file_code',
                    'constraints' => array(
                        'id' => '[0-9a-f]+',
                    ),
                    'defaults' => array(
                        'controller' => 'File\Controller\Ordered'
                    ),
                ),
            ),
            'getFileLocation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/getFileLocation',
                    'defaults' => array(
                        'controller' => 'File\Controller\FileLocation'
                    ),
                ),
            ),
            'getFileInfo' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/getFileInfo/:id',
                    'constraints' => array(
                        'id' => '[0-9a-f]+',
                    ),
                    'defaults' => array(
                        'controller' => 'File\Controller\FileInfo'
                    ),
                ),
            ),
            'setLocation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/setLocation/:file_code',
                    'defaults' => array(
                        'controller' => 'File\Controller\Location'
                    ),
                ),
            ),
            'getFiles' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/getFiles[/]',
                    'defaults' => array(
                        'controller' => 'File\Controller\File'
                    ),
                ),
            ),
            'setLocationByName' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/setLocationByName/:actual_filename',
                    'defaults' => array(
                        'controller' => 'File\Controller\LocationByName'
                    ),
                ),
            ),
            'setMockup' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/setMockup[/:file_code]',
                    'constraints' => array(
                        'file_code' => '[0-9a-f]+',
                    ),
                    'defaults' => array(
                        'controller' => 'File\Controller\Mockup'
                    ),
                ),
            ),
            'getFilePurge' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/purge/getFiles/:server[/:age]',
                    'constraints' => array(
                        'server' => '(S3|MLA|VN)',
                        'age' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'File\Controller\Purge'
                    ),
                ),
            ),
            'setArchiveId' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/file/setArchiveId/:id',
                    'constraints' => array(
                        'id' => '[0-9a-f]+'
                    ),
                    'defaults' => array(
                        'controller' => 'File\Controller\Location'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'File\Controller\FileInfo'              => 'File\Controller\FileInfoController',
            'File\Controller\FileLocation'          => 'File\Controller\FileLocationController',
            'File\Controller\Location'              => 'File\Controller\LocationController',
            'File\Controller\TransferOwnership'     => 'File\Controller\TransferOwnershipController',
            'File\Controller\File'                  => 'File\Controller\FileController',
            'File\Controller\ChildFile'             => 'File\Controller\ChildFileController',
            'File\Controller\Ordered'               => 'File\Controller\OrderedController',
            'File\Controller\LocationByName'        => 'File\Controller\LocationByNameController',
            'File\Controller\Mockup'                => 'File\Controller\MockupController',
            'File\Controller\Purge'                 => 'File\Controller\PurgeController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
