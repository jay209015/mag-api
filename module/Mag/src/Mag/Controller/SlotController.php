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

class SlotController extends DriAbstractRestfulController
{

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $SlotTable \Mag\Model\SlotTable */
        $SlotTable = $serviceManager->get('SlotTable');

        /* @var $Slot \Mag\Model\Slot */
        $Slot = $serviceManager->get('Slot');

        $Slot->exchangeArray($data);

        if($SlotTable->save($Slot)){
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

        /* @var $SlotTable \Mag\Model\SlotTable */
        $SlotTable = $serviceManager->get('SlotTable');

        /* @var $Slot \Mag\Model\Slot */
        $Slot = $SlotTable->fetch($id);

        if($Slot->toArray() != $data) {
            $Slot->exchangeArray($data);
        }else{
            return new JsonModel([
                'Status' => 'Success',
                'Message' => 'No change deteced, nothing to update'
            ]);
        }

        if($SlotTable->save($Slot)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    /**
     * getSlot/key
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $SlotTable \Mag\Model\SlotTable */
        $SlotTable = $serviceManager->get('SlotTable');

        /* @var $Slot \Mag\Model\Slot */
        $Slot = $SlotTable->fetch($id);


        return new JsonModel($Slot->toArray());
    }

    /**
     * getSlot
     * @return JsonModel
     */
    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $SlotTable \Mag\Model\SlotTable */
        $SlotTable = $serviceManager->get('SlotTable');
        $items = $SlotTable->fetchAll();

        return new JsonModel($items);
    }
}
