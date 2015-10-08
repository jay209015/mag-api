<?php
return array(
    'router' => array(
        'routes' => array(
            'Mag' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag',
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Slot'
                    ),
                ),
            ),
            'getSlots' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getSlots',
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Slot'
                    ),
                ),
            ),
            'getConfig' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getConfig[/:id]',
                    'constriants' => array(
                        'id' => '[a-z0-9_]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Config'
                    ),
                ),
            ),
            'setConfig' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/setConfig[/:id]',
                    'constriants' => array(
                        'id' => '[a-z0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Config'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Mag\Controller\Slot'                  => 'Mag\Controller\SlotController',
            'Mag\Controller\Config'                  => 'Mag\Controller\ConfigController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
