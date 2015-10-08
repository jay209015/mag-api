<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mag\Controller;

use Zend\View\Model\JsonModel;

class SlotController extends DriAbstractRestfulController
{
    /**
     * getSlots
     * @return JsonModel
     */
    public function getList()
    {

        $pages = 16;
        $slots_per_page = 6;

        $slots = $pages * $slots_per_page;

        return new JsonModel([
            'slots' =>  $slots
        ]);
    }
}
