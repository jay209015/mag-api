<?php

namespace File\Controller;

use Zend\View\Model\JsonModel;

class LocationController extends DriAbstractRestfulController
{
    /**
     * For get location
     * @return mixed|ApiProblemResponse
     */
    public function get($id)
    {
        try {
            $serviceManager = $this->getServiceLocator();

            $fileTable = $serviceManager->get('FileTableSlave');
            $fileInfo = $fileTable->getFileInfo($id);

            if (!$fileInfo) {
                return $this->invalidArgumentError("File not found");
            }

            $locationModel = $serviceManager->get('Location');
            $locationModel->exchangeArray(array(
                    "file_id" => $fileInfo->file_id,
                    "server" => $this->params()->fromQuery('server', NULL),
                )
            );
            $locationTable = $serviceManager->get('LocationTableSlave');
            $location = $locationTable->getLocation($locationModel);

            if (!$location) {
                return $this->invalidArgumentError("Location not found");
            }

            return new JsonModel(array(
                "server" => $location->server,
                "path" => $location->path,
                "old_path" => $location->old_path,
                "date_added" => $location->date_added,
            ));

        } catch (\Exception $e) {
            \Zend\Debug\Debug::dump($e->getMessage());
            return $this->invalidArgumentError($e->getMessage());
        }
    }

    /**
     * SET LOCATION
     * file/setLocation/:id
     * @param mixed $data
     * @return mixed|void|\ZF\ApiProblem\ApiProblemResponse
     */
    public function create($data)
    {
        try {
            // get file identifier
            $fileCode = $this->params()->fromRoute('file_code');

            // check location object
            $location = isset($data["location"]) ? $data["location"] : array();
            if (empty($location)) {
                return $this->invalidArgumentError('Missing Location Object.');
            }

            $serviceManager = $this->getServiceLocator();

            // get file info
            $fileTable = $serviceManager->get('FileTable');
            $fileId = $fileTable->getFileId($fileCode);
            if (!$fileId) {
                return $this->resourceNotFoundError();
            }

            // add file_id and file_code
            $location['file_id'] = $fileId;
            $location['file_code'] = $fileCode;

            // validate location
            $LocationFilter = $serviceManager->get('LocationFilter');
            $LocationFilter->setData($location);

            if (!$LocationFilter->isValid()) {
                return $this->invalidArgumentError($LocationFilter->getMessages());
            }

            // save location
            $Location = $serviceManager->get('Location');
            $Location->exchangeArray($location);
            $LocationTable = $serviceManager->get('LocationTable');
            if ($LocationTable->saveLocation($Location)) {
                return new JsonModel(array("message" => "Success"));
            } else {
                return $this->notModified();
            }

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }

    }

    /**
     * Method for updating a file archive id
     * @param mixed $id
     * @param mixed $data
     * @return JsonModel
     */
    public function update($id, $data)
    {
        $serviceManager = $this->getServiceLocator();


        /* @var $locationModel \File\Model\Location */
        $locationModel = $serviceManager->get('Location');
        $locationModel->exchangeArray($data);

        /* @var $locationTable \File\Model\LocationTable */
        $locationTable = $serviceManager->get('LocationTableSlave');
        $location = $locationTable->getLocationByID($id);

        if (!$location) {
            return $this->invalidArgumentError("Location not found");
        }

        if(!isset($data['archive_id'])){
            return $this->invalidArgumentError("Archive ID not specified");
        }

        $locationTable->setArchiveID($locationModel);

        /* @var $locationTable \File\Model\LocationTable */
        $locationTable = $serviceManager->get('LocationTableSlave');
        $location = $locationTable->getLocationByID($id);
        $locationModel->exchangeArray((array) $location);

        return new JsonModel(array(
            "message" => "Success",
            "location" => $locationModel->toArray()
        ));

    }
}
