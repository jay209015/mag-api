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

class FileController extends DriAbstractRestfulController
{

    /**
     * For creation of new files
     * @return mixed|ApiProblemResponse
     */
    public function create($data)
    {
        try {

            if (isset($data['files']) && !empty($data['files'])) {

                $sm             = $this->getServiceLocator();
                $Files          = $sm->get('Files');
                $FileTable      = $sm->get('FileTable');
                $FileService    = $sm->get('FileService');

                foreach ($data['files'] as $file) {
                    $FileFilter = $sm->get('FileFilter');
                    $FileFilter->setData($file);
                    if (!$FileFilter->isValid()) {
                        return $this->invalidArgumentError($FileFilter->getMessages());
                    } else {
                        $Files->exchangeArray($file, $data);
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
                }

            } else {
                return $this->invalidArgumentError("Missing Files Object.");
            }

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }

    /**
     * For formatting correct file array
     * Creation of file is always 1 at a time.
     */

    protected function formatFileData($data)
    {
        $file = array();

        if (count($data['files']) == 1) {
            foreach ($data['files'] as $f) {
                $file = $f;
            }
        } else {
            $file = $data['files'];
        }

        return $file;
    }

    /**
     * For get files
     * @return mixed|ApiProblemResponse
     */
    public function getList()
    {
        try {
            $sm     = $this->getServiceLocator();
            $config = $sm->get('Configuration');
            $params = array();

            // check customer id
            if ($this->params()->fromQuery('customer_id', null) !== null) {
                $params['customer_id'] = $this->params()->fromQuery('customer_id', null);
            } else if ($this->params()->fromQuery('visitor_id', null) !== null) { // check visitor id
                $params['visitor_id'] = $this->params()->fromQuery('visitor_id', null);
                $params['customer_id'] = 0;
            }

            // check filter
            if ($this->params()->fromQuery('filter', null) !== null) {
                $params['filter'] = $this->params()->fromQuery('filter', null);

                // check filter if it's in the list
                if (in_array($params['filter'], array_keys($config['file_filters']))) {
                    $params['filter'] = "'" . implode("','", $config['file_filters'][$params['filter']]) . "'";
                } else {
                    unset($params['filter']);
                }
            }

            // if no parameter passed, do not process
            if (sizeof($params) == 0) {
                return $this->invalidArgumentError("Missing parameters");
            }

            // set offset to 0 if not set
            $params['offset'] = (int)$this->params()->fromQuery('offset', 0);

            // set limit
            $params['limit'] = (int)$this->params()->fromQuery('limit', 0);
            if ($params['limit'] == 0) {
                unset($params['limit']);
                unset($params['offset']);
            }

            // set page to 1 if not set
            $params['page'] = (int)$this->params()->fromQuery('page', 1);

            $serviceManager = $this->getServiceLocator();

            $fileService = $serviceManager->get("FileServiceSlave");
            $result = $fileService->getFiles($params);

            return new JsonModel($result);

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }
    }

}
