<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace File\Controller;

use Zend\View\Model\JsonModel;

class ChildFileController extends DriAbstractRestfulController
{
    
    /**
     * For creation of new files
     * @return mixed|ApiProblemResponse
     */
    public function create($data)
    {
        try {

            $fileCode = $this->params()->fromRoute('file_code', NULL);

            if (isset($data['files']) && !empty($data['files'])) {

                $sm             = $this->getServiceLocator();
                $Files          = $sm->get('Files');
                $FileTable      = $sm->get('FileTable');
                $FileService    = $sm->get('FileService');

                $parentFileId = $fileCode ? $FileTable->getFileId($fileCode) : 0;
                if (!$parentFileId) {
                    return $this->resourceNotFoundError();
                }

                // get the file info
                $file = $data['files'];

                $FileFilter = $sm->get('FileFilter');
                $FileFilter->setData($file);
                if (!$FileFilter->isValid()) {
                    return $this->invalidArgumentError($FileFilter->getMessages());
                } else {
                    $Files->exchangeArray($data['files'], $data);
                    $Files->setParentFileId($parentFileId);

                    $fileData = $Files->getFile();
                    $fileId = $FileTable->insert($fileData);

                    $Location = $sm->get('Location');
                    $LocationTable = $sm->get('LocationTable');
                    $locations = $Files->getLocations($fileId);
                    $LocationFilter = $sm->get('LocationFilter');
                    $Location->saveLocations($locations, $LocationTable, $LocationFilter);

                    $Page = $sm->get('Page');
                    $PageTable = $sm->get('PageTable');
                    $pages = $Files->getPages($fileId);
                    $PageFilter = $sm->get('PageFilter');
                    $Page->savePages($pages, $PageTable, $PageFilter);

                    if (isset($data['thumbnail'])) {
                        $Thumbnail = $sm->get('Thumbnail');
                        $ThumbnailTable = $sm->get('ThumbnailTable');
                        $ThumbnailFilter = $sm->get('ThumbnailFilter');
                        $Thumbnail->saveThumbnails($fileId, $Files->file_code, $data['thumbnail'], $ThumbnailTable, $ThumbnailFilter);
                    }

                    if (isset($data['ordered'])) {
                        $Ordered = $sm->get('Ordered');
                        $OrderedTable = $sm->get('OrderedTable');
                        $OrderedFilter = $sm->get('OrderedFilter');
                        $Ordered->saveOrdered($fileId, $Files->file_code, $data['ordered'], $OrderedTable, $OrderedFilter);
                    }

                    return new JsonModel($FileService->getFileDocument($fileId));
                }
            } else {
                return $this->invalidArgumentError("Missing Files Object.");
            }

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }

}
