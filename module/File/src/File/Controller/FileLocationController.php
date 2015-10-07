<?php

namespace File\Controller;

use Zend\View\Model\JsonModel;

class FileLocationController extends DriAbstractRestfulController
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
                "file_id"           => $fileInfo->file_id,
                "name"              => $fileInfo->name,
                "actual_filename"   => $fileInfo->actual_filename,
                "extension"         => $fileInfo->extension,
                "content_type"      => $fileInfo->content_type,
                "size"              => $fileInfo->size,
                "created"           => $fileInfo->created,
                "origin"            => $fileInfo->origin,
                "file_code"         => $fileInfo->file_code,
                "_id"               => $fileInfo->file_code,
                "location"          => array(array(
                    "server" => $location->server,
                    "path" => $location->path,
                    "old_path" => $location->old_path,
                    "date_added" => $location->date_added,
                )),
            ));

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }
}
