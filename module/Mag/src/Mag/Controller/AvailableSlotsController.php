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

class AvailableSlotsController extends DriAbstractRestfulController
{
    /**
     * getAvailableSlots/id
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $MagazineTable \Mag\Model\MagazineTable */
        $MagazineTable = $serviceManager->get('MagazineTable');
        $Magazine = $MagazineTable->fetch($id);
        
        /* @var $OrderTable \Mag\Model\OrderTable */
        $OrderTable = $serviceManager->get('OrderTable');

        $slots = $OrderTable->getTotalSlotsByMagazine($Magazine);

        return new JsonModel(['slots' => $slots]);
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
