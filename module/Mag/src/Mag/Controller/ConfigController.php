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

class ConfigController extends DriAbstractRestfulController
{

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();

        /* @var $ConfigTable \Mag\Model\ConfigTable */
        $ConfigTable = $serviceManager->get('ConfigTable');

        /* @var $ConfigItem \Mag\Model\ConfigItem */
        $ConfigItem = $serviceManager->get('ConfigItem');

        $ConfigItem->exchangeArray($data);

        if($ConfigTable->save($ConfigItem)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    public function update($id, $data)
    {
        $key = $id;
        $value = $data['value'];

        $serviceManager = $this->getServiceLocator();

        /* @var $ConfigTable \Mag\Model\ConfigTable */
        $ConfigTable = $serviceManager->get('ConfigTable');

        /* @var $ConfigItem \Mag\Model\ConfigItem */
        $ConfigItem = $ConfigTable->fetch($key);

        if($ConfigItem->value != $data['value']) {
            $ConfigItem->value = $data['value'];
        }else{
            return new JsonModel([
                'Status' => 'Success',
                'Message' => 'No change deteced, nothing to update'
            ]);
        }

        if($ConfigTable->save($ConfigItem)){
            $response = ['Status' => 'Success'];
        }else{
            $response = ['Status' => 'Failure'];
        }

        return new JsonModel($response);
    }

    /**
     * getConfig/key
     * @return JsonModel
     */
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $ConfigTable \Mag\Model\ConfigTable */
        $ConfigTable = $serviceManager->get('ConfigTable');

        /* @var $ConfigItem \Mag\Model\ConfigItem */
        $ConfigItem = $ConfigTable->fetch($id);


        return new JsonModel($ConfigItem->toArray());
    }

    /**
     * getConfig
     * @return JsonModel
     */
    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        /* @var $ConfigTable \Mag\Model\ConfigTable */
        $ConfigTable = $serviceManager->get('ConfigTable');
        $items = $ConfigTable->fetchAll();

        return new JsonModel($items);
    }
}
