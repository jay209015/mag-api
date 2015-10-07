<?php

namespace File\Controller;

use Zend\View\Model\JsonModel;

class StatusController extends DriAbstractRestfulController
{
    /**
     * For posting of status
     * @return mixed|ApiProblemResponse
     */
    public function create($content)
    {
        try {
            $serviceManager = $this->getServiceLocator();
            $StatusFilter = $serviceManager->get('StatusFilter');
            $StatusFilter->setData($content);
            if (!$StatusFilter->isValid()) {
                return $this->invalidArgumentError($StatusFilter->getMessages());
            } else {
                // get file_code from route
                $fileCode = $this->params()->fromRoute('id');

                $redis = $serviceManager->get('Redis');
                if ($redis->setItem($fileCode, serialize($content))) {
                    return new JsonModel(array("message" => "Success"));
                } else {
                    return $this->invalidArgumentError("Unable to store key in cache");
                }
            }
        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }

    /**
     * For get status
     * @return mixed|ApiProblemResponse
     */
    public function get($id)
    {

        try {
            $serviceManager = $this->getServiceLocator();
            $redis = $serviceManager->get('Redis');

            if ($redis->hasItem($id)) {
                return new JsonModel(unserialize($redis->getItem($id)));
            } else {
                return $this->invalidArgumentError("Key not found");
            }

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }

}
