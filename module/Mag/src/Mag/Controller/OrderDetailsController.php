<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mag\Controller;

use Zend\Di\ServiceLocator;
use Zend\View\Model\JsonModel;

class OrderDetailsController extends DriAbstractRestfulController
{
    /**
     * getOrderDetails/key
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $OrderTable \Mag\Model\OrderTable */
        $OrderTable = $serviceManager->get('OrderTable');

        /* @var $Order \Mag\Model\Order */
        if($Order = $OrderTable->fetch($id)){
            /* @var $OrderItemTable \Mag\Model\OrderItemTable */
            $OrderItemTable = $serviceManager->get('OrderItemTable');
            $OrderItems = $OrderItemTable->fetchByOrderId($Order->id);

            /* @var $UserTable \Mag\Model\UserTable */
            $UserTable = $serviceManager->get('UserTable');
            $User = $UserTable->fetch($Order->user_id);

            /* @var $MagazineTable \Mag\Model\MagazineTable */
            $MagazineTable = $serviceManager->get('MagazineTable');
            $Magazine = $MagazineTable->fetch($Order->magazine_id);

            $results = [
                'Order' => $Order->toArray(),
                'OrderItems' => $OrderItems->toArray(),
                'User' => $User->toArray(),
                'Magazine' => $Magazine->toArray(),
            ];

            return new JsonModel($results);
        }else{
            return new JsonModel([
                'Status' => 'Failure',
                'Message' => 'Order not found'
            ]);
        }
    }
}
