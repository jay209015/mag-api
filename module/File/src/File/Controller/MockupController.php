<?php

namespace File\Controller;

use Zend\View\Model\JsonModel;

class MockupController extends DriAbstractRestfulController
{

    /**
     * For setting mockups
     * @return mixed|ApiProblemResponse
     */
    public function create($data)
    {
        try {
            // get file identifier
            $fileCode = $this->params()->fromRoute('file_code');

            // check mockup object           
            if (!isset($data['mockup']) || empty($data['mockup'])) {
                return $this->invalidArgumentError('Missing Mockup Object.');
            }

            $sm = $this->getServiceLocator();
            $FileTable = $sm->get('FileTableSlave');

            $fileId = $FileTable->getFileId($fileCode);
            if (!$fileId) {
                return $this->resourceNotFoundError();
            }

            $Mockup = $sm->get('Mockup');
            $MockupTable = $sm->get('MockupTable');
            $MockupFilter = $sm->get('MockupFilter');
            $Mockup->saveMockups($fileId, $fileCode, $data['mockup'], $MockupTable, $MockupFilter);

            return new JsonModel(array("message" => "Success"));

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }

    }
}
