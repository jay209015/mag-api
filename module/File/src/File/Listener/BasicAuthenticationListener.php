<?php
/**
 * Created by PhpStorm.
 * User: WEBPRODEV\pgamilde
 * Date: 3/6/15
 * Time: 9:50 PM
 */

namespace File\Listener;

use File\Service\BasicAuthenticationService;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class BasicAuthenticationListener
{
    protected $BasicAuthenticationService;

    public function __construct(BasicAuthenticationService $BasicAuthenticationService) {
        $this->BasicAuthenticationService = $BasicAuthenticationService;
    }

    /**
     * @param MvcEvent $event
     * @return ApiProblemResponse
     */
    public function __invoke(MvcEvent $event)
    {

        if (!$this->BasicAuthenticationService->authenticate()) {
            $event->getResponse()->setStatusCode(401);

            $model = new JsonModel(
                array(
                    "code" => "InvalidCredentials",
                    "message" => "Invalid Client Credentials."
                )
            );

            $event->setResult($model);

            return $model;
        }

    }
} 