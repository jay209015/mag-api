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

class OrderItemController extends DriAbstractRestfulController
{

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $OrderItemTable \Mag\Model\OrderItemTable */
        $OrderItemTable = $serviceManager->get('OrderItemTable');

        /* @var $OrderItem \Mag\Model\OrderItem */
        $OrderItem = $serviceManager->get('OrderItem');

        $OrderItem->exchangeArray($data);

        if($OrderItemTable->save($OrderItem)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    public function update($id, $data)
    {
        $value = $data['value'];

        $serviceManager = $this->getServiceLocator();

        /* @var $OrderItemTable \Mag\Model\OrderItemTable */
        $OrderItemTable = $serviceManager->get('OrderItemTable');

        /* @var $OrderItem \Mag\Model\OrderItem */
        $OrderItem = $OrderItemTable->fetch($id);

        if($OrderItem->toArray() != $data) {
            $OrderItem->exchangeArray($data);
        }else{
            return new JsonModel([
                'Status' => 'Success',
                'Message' => 'No change deteced, nothing to update'
            ]);
        }

        if($OrderItemTable->save($OrderItem)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    /**
     * getOrderItem/key
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $OrderItemTable \Mag\Model\OrderItemTable */
        $OrderItemTable = $serviceManager->get('OrderItemTable');

        /* @var $OrderItem \Mag\Model\OrderItem */
        $OrderItem = $OrderItemTable->fetch($id);


        return new JsonModel($OrderItem->toArray());
    }

    /**
     * getOrderItem
     * @return JsonModel
     */
    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $OrderItemTable \Mag\Model\OrderItemTable */
        $OrderItemTable = $serviceManager->get('OrderItemTable');
        $items = $OrderItemTable->fetchAll();

        return new JsonModel($items);
    }
}
