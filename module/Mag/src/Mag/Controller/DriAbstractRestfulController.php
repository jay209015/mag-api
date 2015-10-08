<?php

namespace Mag\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Json\Json;
use Zend\Stdlib\RequestInterface as Request;
use Zend\View\Model\JsonModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
/**
 * Class DriAbstractRestfulController
 * @package File\Controller
 */
abstract class DriAbstractRestfulController extends AbstractRestfulController
{
    const CONTENT_TYPE_JSON = 'json';    

    protected $collectionOptions = array();
    protected $resourceOptions = array();

    protected $eventIdentifier = 'File\Controller';


    /**
     * @param mixed $data
     * @return mixed|ApiProblemResponse
     */
    public function create($data)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @param mixed $id
     * @return mixed|ApiProblemResponse
     */
    public function delete($id)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @return mixed|ApiProblemResponse
     */
    public function deleteList($data)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @param mixed $id
     * @return mixed|ApiProblemResponse
     */
    public function get($id)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @return mixed|ApiProblemResponse
     */
    public function getList()
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @param null $id
     * @return mixed|ApiProblemResponse
     */
    public function head($id = null)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @return mixed|ApiProblemResponse
     */
    public function options()
    {
        $response = $this->getResponse();

        // if in Options array, Allow
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Methods', implode(',', $this->_getOptions()));

        return $response;
    }

    /**
     * @return array
     */
    protected function _getOptions()
    {
        if ($this->params()->fromRoute($this->getIdentifierName(), false)) {
            return $this->resourceOptions;
        }

        return $this->collectionOptions;
    }

    /**
     * @param $id
     * @param $data
     * @return array|ApiProblemResponse
     */
    public function patch($id, $data)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @param mixed $data
     * @return mixed|ApiProblemResponse
     */
    public function replaceList($data)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @param mixed $data
     * @return mixed|ApiProblemResponse
     */
    public function patchList($data)
    {
        return $this->methodNotAllowedError();
    }

    /**
     * @param mixed $id
     * @param mixed $data
     * @return mixed|ApiProblemResponse
     */
    public function update($id, $data)
    {
        return $this->methodNotAllowedError();
    }

    public function processPostData(Request $request)
    {
        if ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON)) {
            $requestContent = preg_replace('/([a-zA-Z]\w+\(")([A-Z0-9a-z\:\.\-]+)("\))/', '"$2"', $request->getContent());            
            $data = Json::decode($requestContent, $this->jsonDecodeType);
        } else {
            $data = $request->getPost()->toArray();
        }

        return $this->create($data);
    }

    public function invalidArgumentError($message)
    {
        $this->response->setStatusCode(409);

        // process the messages if it's an array
        if (is_array($message)) {
            $content = "";
            foreach ($message as $errors) {
                foreach ($errors as $error) {
                    $content .= $error;
                }
            }

            $message = $content;
        }

        return new JsonModel(
            array(
                "code" => "InvalidArgument",
                "message" => $message
            )
        );
    }

    public function notModified()
    {
        $this->response->setStatusCode(304);

        return new JsonModel(array());
    }

    public function resourceNotFoundError()
    {
        $this->response->setStatusCode(404);

        return new JsonModel(array("message" => "File Not Found."));
    }

    public function methodNotAllowedError()
    {
        $this->response->setStatusCode(405);

        return new JsonModel(
            array(
                "code" => "MethodNotAllowed",
                "message" => $this->request->getMethod() . " method is not allowed."
            )
        );
    }

}
