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

class OrderController extends DriAbstractRestfulController
{

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $OrderTable \Mag\Model\OrderTable */
        $OrderTable = $serviceManager->get('OrderTable');

        /* @var $Order \Mag\Model\Order */
        $Order = $serviceManager->get('Order');

        $Order->exchangeArray($data);

        if($OrderTable->save($Order)){
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

        /* @var $OrderTable \Mag\Model\OrderTable */
        $OrderTable = $serviceManager->get('OrderTable');

        /* @var $Order \Mag\Model\Order */
        $Order = $OrderTable->fetch($id);

        if($Order->toArray() != $data) {
            $Order->exchangeArray($data);
        }else{
            return new JsonModel([
                'Status' => 'Success',
                'Message' => 'No change deteced, nothing to update'
            ]);
        }

        if($OrderTable->save($Order)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    /**
     * getOrder/key
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $OrderTable \Mag\Model\OrderTable */
        $OrderTable = $serviceManager->get('OrderTable');

        /* @var $Order \Mag\Model\Order */
        $Order = $OrderTable->fetch($id);


        return new JsonModel($Order->toArray());
    }

    /**
     * getOrder
     * @return JsonModel
     */
    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $OrderTable \Mag\Model\OrderTable */
        $OrderTable = $serviceManager->get('OrderTable');
        $items = $OrderTable->fetchAll();

        return new JsonModel($items);
    }
}
