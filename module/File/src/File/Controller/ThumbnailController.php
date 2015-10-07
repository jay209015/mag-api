<?php

namespace File\Controller;

use Zend\View\Model\JsonModel;

class ThumbnailController extends DriAbstractRestfulController
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

            // check thumbnail object
            if (!isset($data['thumbnail']) || empty($data['thumbnail'])) {
                return $this->invalidArgumentError('Missing thumbnail Object.');
            }

            $sm = $this->getServiceLocator();
            $FileTable = $sm->get('FileTableSlave');

            $fileId = $FileTable->getFileId($fileCode);
            if (!$fileId) {
                return $this->resourceNotFoundError();
            }

            $Thumbnail = $sm->get('Thumbnail');
            $ThumbnailTable = $sm->get('ThumbnailTable');
            $ThumbnailFilter = $sm->get('ThumbnailFilter');
            $Thumbnail->saveThumbnails($fileId, $fileCode, $data['thumbnail'], $ThumbnailTable, $ThumbnailFilter);

            return new JsonModel(array("message" => "Success"));

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }

    }

}
