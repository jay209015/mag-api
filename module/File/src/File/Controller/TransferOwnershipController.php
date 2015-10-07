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

class TransferOwnershipController extends DriAbstractRestfulController
{
    /**
     * For transfer ownership from vid to cid
     * @return mixed|ApiProblemResponse
     */
    public function replaceList($data)
    {
        try {
            $serviceManager = $this->getServiceLocator();

            $fileModel = $serviceManager->get('File');
            $fileModel->exchangeArray($data);

            $TransferOwnershipFilter = $serviceManager->get('TransferOwnershipFilter');
            $TransferOwnershipFilter->setData($data);
            if (!$TransferOwnershipFilter->isValid()) {
                return $this->invalidArgumentError($TransferOwnershipFilter->getMessages());
            }

            $fileTable = $serviceManager->get('FileTable');
            $affectedRows = $fileTable->transferOwnership($fileModel);

            if ($affectedRows > 0) {
                return new JsonModel(array("message" => "Success"));
            } else {
                return $this->invalidArgumentError("Record not found");
            }


        } catch (\Exception $e) {
            return $this->invalidArgumentError($e->getMessage());
        }

    }

}
