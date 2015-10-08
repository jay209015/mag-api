<?php
namespace Mag\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Http\PhpEnvironment\Request;

class BasicAuthenticationService implements AdapterInterface
{
    protected $clients;
    protected $httpRequest;

    /**
     * @param Request $httpRequest
     * @param array $clients
     */
    public function __construct(Request $httpRequest, array $clients)
    {
        $this->clients = $clients;
        $this->httpRequest = $httpRequest;
    }

    public function authenticate()
    {
        $headers = $this->httpRequest->getHeaders();

        // Check Authorization header presence
        if (!$headers->has('Authorization')) {
            return false;
        }

        // Check Authorization prefix
        $authorization = $headers->get('Authorization')->getFieldValue();
        if (strpos($authorization, 'Basic') !== 0) {
            return false;
        }

        $clientCredential = $this->extractClientCredential($authorization);

        // check if client is valid
        if (!isset($this->clients[$clientCredential["clientId"]])) {
            return false;
        }

        // check if client secret is valid
        if ($this->clients[$clientCredential["clientId"]]["secret"] != $clientCredential["secret"]) {
            return false;
        }

        return true;
    }

    /**
     * @param $authorization
     * @return array
     */
    public function extractClientCredential($authorization)
    {
        $authBasic = explode(' ',$authorization);
        $clientCredential = isset($authBasic[1]) ? explode(':', base64_decode($authBasic[1])) : array();

        return array(
            "clientId" => isset($clientCredential[0]) ? $clientCredential[0] : null,
            "secret" => isset($clientCredential[1]) ? $clientCredential[1] : null,
        );
    }

}