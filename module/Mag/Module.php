<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mag;


use Mag\Listener\BasicAuthenticationListener;

use Mag\Service\BasicAuthenticationService;

use Mag\Model\ConfigItem;
use Mag\Model\ConfigTable;
use Mag\Model\User;
use Mag\Model\UserTable;
use Mag\Model\Magazine;
use Mag\Model\MagazineTable;
use Mag\Model\Slot;
use Mag\Model\SlotTable;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\Cache\StorageFactory;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // attach auth listener on every controller dispatch event
        $BasicAuthenticationService = $e->getApplication()->getServiceManager()->get("BasicAuthenticationService");
        $authListener = new BasicAuthenticationListener($BasicAuthenticationService);
        $eventManager->getSharedManager()->attach('Mag', 'dispatch', $authListener, 0);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 0);

    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'Zend\Loader\ClassMapAutoloader' => array(
                    __DIR__ . '/autoload_classmap.php',
                ),
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onDispatchError(MvcEvent $e)
    {
        return $this->getJsonModelError($e);
    }

    public function onRenderError(MvcEvent $e)
    {
        return $this->getJsonModelError($e);
    }

    public function getJsonModelError(MvcEvent $e)
    {
        $error = $e->getError();
        if (!$error) {
            return;
        }

        $response = $e->getResponse();
        $exception = $e->getParam('exception');
        $exceptionJson = array();
        if ($exception) {
            $exceptionJson = array(
                'class' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'stacktrace' => $exception->getTraceAsString()
            );
        }

        $errorJson = array(
            'message'   => 'An error occurred during execution; please try again later.',
            'error'     => $error,
            'exception' => $exceptionJson,
        );

        if ($error == 'error-router-no-match') {
            $errorJson['message'] = 'Resource not found.';
        }

        $model = new JsonModel(array('errors' => array($errorJson)));

        $e->setResult($model);

        return $model;
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'BasicAuthenticationService' => function ($sm) {
                    $config = $sm->get('Configuration');
                    $request     = $sm->get('Request');

                    return new BasicAuthenticationService($request, $config['clients']);
                },
                'ConfigItem' => function($sm) {
                    return new ConfigItem();
                },
                'ConfigTable' => function($sm) {
                    $tableGateway = $sm->get('ConfigTableGateway');
                    return new ConfigTable($tableGateway);
                },
                'ConfigTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ConfigItem());
                    return new TableGateway('config', $dbAdapter, null, $resultSetPrototype);
                },
                'User' => function($sm) {
                    return new User();
                },
                'UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    return new UserTable($tableGateway);
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'Magazine' => function($sm) {
                    return new Magazine();
                },
                'MagazineTable' => function($sm) {
                    $tableGateway = $sm->get('MagazineTableGateway');
                    return new MagazineTable($tableGateway);
                },
                'MagazineTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Magazine());
                    return new TableGateway('magazine', $dbAdapter, null, $resultSetPrototype);
                },
                'Slot' => function($sm) {
                    return new Slot();
                },
                'SlotTable' => function($sm) {
                    $tableGateway = $sm->get('SlotTableGateway');
                    return new SlotTable($tableGateway);
                },
                'SlotTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Slot());
                    return new TableGateway('slot', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }

}
