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

class MagazineController extends DriAbstractRestfulController
{

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $MagazineTable \Mag\Model\MagazineTable */
        $MagazineTable = $serviceManager->get('MagazineTable');

        /* @var $Magazine \Mag\Model\Magazine */
        $Magazine = $serviceManager->get('Magazine');

        $Magazine->exchangeArray($data);

        if($MagazineTable->save($Magazine)){
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

        /* @var $MagazineTable \Mag\Model\MagazineTable */
        $MagazineTable = $serviceManager->get('MagazineTable');

        /* @var $Magazine \Mag\Model\Magazine */
        $Magazine = $MagazineTable->fetch($id);

        if($Magazine->toArray() != $data) {
            $Magazine->exchangeArray($data);
        }else{
            return new JsonModel([
                'Status' => 'Success',
                'Message' => 'No change deteced, nothing to update'
            ]);
        }

        if($MagazineTable->save($Magazine)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    /**
     * getMagazine/key
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $MagazineTable \Mag\Model\MagazineTable */
        $MagazineTable = $serviceManager->get('MagazineTable');

        /* @var $Magazine \Mag\Model\Magazine */
        $Magazine = $MagazineTable->fetch($id);


        return new JsonModel($Magazine->toArray());
    }

    /**
     * getMagazine
     * @return JsonModel
     */
    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $MagazineTable \Mag\Model\MagazineTable */
        $MagazineTable = $serviceManager->get('MagazineTable');
        $items = $MagazineTable->fetchAll();

        return new JsonModel($items);
    }
}
