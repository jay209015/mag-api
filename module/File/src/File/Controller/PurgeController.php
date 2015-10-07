<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 2:10 AM
 */

namespace File\Controller;

use Zend\View\Model\JsonModel;

class PurgeController extends DriAbstractRestfulController
{

    public function getList($age=1, $server='S3')
    {
        try {
            $serviceManager = $this->getServiceLocator();

            /* @var $locationTable \File\Model\LocationTable */
            $locationTable = $serviceManager->get('LocationTableSlave');
            $files = $locationTable->getLocationsPurge($server, $age);


            return new JsonModel($files);
        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }

    public function delete($id)
    {

    }

    public function delete_list()
    {

    }
} 