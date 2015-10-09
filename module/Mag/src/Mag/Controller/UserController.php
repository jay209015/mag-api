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

class UserController extends DriAbstractRestfulController
{

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $UserTable \Mag\Model\UserTable */
        $UserTable = $serviceManager->get('UserTable');

        /* @var $User \Mag\Model\User */
        $User = $serviceManager->get('User');

        $User->exchangeArray($data);

        if($UserTable->save($User)){
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

        /* @var $UserTable \Mag\Model\UserTable */
        $UserTable = $serviceManager->get('UserTable');

        /* @var $User \Mag\Model\User */
        $User = $UserTable->fetch($id);

        if($User->toArray() != $data) {
            $User->exchangeArray($data);
        }else{
            return new JsonModel([
                'Status' => 'Success',
                'Message' => 'No change deteced, nothing to update'
            ]);
        }

        if($UserTable->save($User)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    /**
     * getUser/key
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $UserTable \Mag\Model\UserTable */
        $UserTable = $serviceManager->get('UserTable');

        /* @var $User \Mag\Model\User */
        $User = $UserTable->fetch($id);


        return new JsonModel($User->toArray());
    }

    /**
     * getUser
     * @return JsonModel
     */
    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $UserTable \Mag\Model\UserTable */
        $UserTable = $serviceManager->get('UserTable');
        $items = $UserTable->fetchAll();

        return new JsonModel($items);
    }
}
