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
            'getUser' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getUser[/:id]',
                    'constriants' => array(
                        'id' => '[0-9_]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\User'
                    ),
                ),
            ),
            'setUser' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/setUser[/:id]',
                    'constriants' => array(
                        'id' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\User'
                    ),
                ),
            ),
            'getMagazine' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getMagazine[/:id]',
                    'constriants' => array(
                        'id' => '[0-9_]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Magazine'
                    ),
                ),
            ),
            'setMagazine' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/setMagazine[/:id]',
                    'constriants' => array(
                        'id' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Magazine'
                    ),
                ),
            ),
            'getSlot' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getSlot[/:id]',
                    'constriants' => array(
                        'id' => '[0-9_]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Slot'
                    ),
                ),
            ),
            'setSlot' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/setSlot[/:id]',
                    'constriants' => array(
                        'id' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Slot'
                    ),
                ),
            ),
            'getOrder' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getOrder[/:id]',
                    'constriants' => array(
                        'id' => '[0-9_]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Order'
                    ),
                ),
            ),
            'setOrder' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/setOrder[/:id]',
                    'constriants' => array(
                        'id' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\Order'
                    ),
                ),
            ),
            'getOrderItem' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getOrderItem[/:id]',
                    'constriants' => array(
                        'id' => '[0-9_]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\OrderItem'
                    ),
                ),
            ),
            'setOrderItem' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/setOrderItem[/:id]',
                    'constriants' => array(
                        'id' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\OrderItem'
                    ),
                ),
            ),
            'getOrderDetails' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getOrderDetails/:id',
                    'constriants' => array(
                        'id' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\OrderDetails'
                    ),
                ),
            ),
            'getAvailableSlots' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mag/getAvailableSlots/:id',
                    'constriants' => array(
                        'id' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'Mag\Controller\AvailableSlots'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Mag\Controller\Config'                 => 'Mag\Controller\ConfigController',
            'Mag\Controller\User'                   => 'Mag\Controller\UserController',
            'Mag\Controller\Magazine'               => 'Mag\Controller\MagazineController',
            'Mag\Controller\Slot'                   => 'Mag\Controller\SlotController',
            'Mag\Controller\Order'                  => 'Mag\Controller\OrderController',
            'Mag\Controller\OrderItem'              => 'Mag\Controller\OrderItemController',
            'Mag\Controller\OrderDetails'           => 'Mag\Controller\OrderDetailsController',
            'Mag\Controller\AvailableSlots'         => 'Mag\Controller\AvailableSlotsController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
