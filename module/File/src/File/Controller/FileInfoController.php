<?php

namespace File\Controller;

use Zend\View\Model\JsonModel;

class FileInfoController extends DriAbstractRestfulController
{
    /**
     * For get file info
     * @return mixed|ApiProblemResponse
     */
    public function get($id)
    {

        try {
            $serviceManager = $this->getServiceLocator();

            // file
            $fileTable = $serviceManager->get('FileTable');
            $fileInfo = $fileTable->getFileInfo($id);

            if (!$fileInfo) {
                return $this->invalidArgumentError("File not found");
            }

            $fileService = $serviceManager->get('FileService');
            $fileDocument = $fileService->getFileDocument($fileInfo->file_id);

            return new JsonModel($fileDocument);

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }
}
