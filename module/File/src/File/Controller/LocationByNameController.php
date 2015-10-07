<?php
/**
 * Created by PhpStorm.
 * User: prince
 * Date: 3/15/15
 * Time: 1:55 PM
 */

namespace File\Controller;


use Zend\View\Model\JsonModel;

class LocationByNameController extends DriAbstractRestfulController
{
    public function create($data)
    {
        try {
            // get file identifier
            $actualFileName = $this->params()->fromRoute('actual_filename');

            // check location object
            $location = isset($data["location"]) ? $data["location"] : array();
            if (empty($location)) {
                return $this->invalidArgumentError('Missing Location Object.');
            }

            $serviceManager = $this->getServiceLocator();

            // get file info
            $FileTable = $serviceManager->get('FileTable');
            $file = $FileTable->getFileByActualFileName($actualFileName);
            if (!$file) {
                return $this->resourceNotFoundError();
            }

            // add file_id and file_code
            $location['file_id'] = $file->file_id;
            $location['file_code'] = $file->file_code;

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
                return new JsonModel(array(
                    "file_id"           => $file->file_id,
                    "name"              => $file->name,
                    "actual_filename"   => $file->actual_filename,
                    "extension"         => $file->extension,
                    "content_type"      => $file->content_type,
                    "size"              => $file->size,
                    "created"           => $file->created,
                    "origin"            => $file->origin,
                    "file_code"         => $file->file_code,
                    "_id"               => $file->file_code,
                    "location"          => array(array(
                        "server" => $Location->server,
                        "path" => $Location->path,
                        "old_path" => $Location->old_path,
                        "date_added" => $Location->date_added,
                    )),
                ));

            } else {
                return $this->notModified();
            }

        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }

    }
} 