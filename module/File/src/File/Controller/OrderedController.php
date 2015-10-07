<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/10/15
 * Time: 2:10 AM
 */

namespace File\Controller;

use Zend\View\Model\JsonModel;

class OrderedController extends DriAbstractRestfulController
{
    /**
     * End point for setAsOrdered API
     * Route : /file/setAsOrdered/:id
     * @param mixed $data
     * @return mixed|void|JsonModel|\ZF\ApiProblem\ApiProblemResponse
     */
    public function create($data)
    {
        try {
            // get file_code from route
            $fileCode = $this->params()->fromRoute('file_code');

            // check if ordered data is set
            $ordered = isset($data["ordered"]) ? $data["ordered"] : array();
            if (empty($ordered)) {
                return $this->invalidArgumentError('Missing Ordered Object.');
            }

            $serviceManager = $this->getServiceLocator();




            // get file info
            $fileTable = $serviceManager->get('FileTable');
            $fileId = $fileTable->getFileId($fileCode);
            if (!$fileId) {
                return $this->invalidArgumentError('File not found.');
            }

            $ordered['file_id'] = $fileId;
            $ordered['file_code'] = $fileCode;

            // validate ordered data
            $OrderedFilter = $serviceManager->get('OrderedFilter');
            $OrderedFilter->setData($ordered);
            if (!$OrderedFilter->isValid()) {
                return $this->invalidArgumentError($OrderedFilter->getMessages());
            }

            $Ordered = $serviceManager->get('Ordered');
            $Ordered->exchangeArray($ordered);

            $OrderedTable = $serviceManager->get('OrderedTable');
            if ($OrderedTable->saveOrdered($Ordered)) {
                return new JsonModel(array("message" => "Success"));
            } else {
                return $this->notModified();
            }
        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }
} 