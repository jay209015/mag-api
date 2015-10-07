<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace File;

use File\Model\File;
use File\Model\Files;
use File\Model\FileTable;
use File\Model\Location;
use File\Model\LocationTable;
use File\Model\Mockup;
use File\Model\MockupTable;
use File\Model\Ordered;
use File\Model\OrderedTable;
use File\Model\Page;
use File\Model\PageTable;
use File\Model\Thumbnail;
use File\Model\ThumbnailTable;
use File\Validation\MockupValidation;
use File\Service\PageService;
use File\Service\MockupService;
use File\Service\OrderedService;
use File\Service\FileService;
use File\Service\LocationService;
use File\Service\PurgeService;
use File\Service\ThumbnailService;
use File\Listener\BasicAuthenticationListener;
use File\Service\BasicAuthenticationService;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\Cache\StorageFactory;

use File\Filter\TransferOwnershipFilter;
use File\Filter\FileFilter;
use File\Filter\LocationFilter;
use File\Filter\PageFilter;
use File\Filter\ThumbnailFilter;
use File\Filter\OrderedFilter;
use File\Filter\MockupFilter;
use File\Filter\StatusFilter;

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
        $eventManager->getSharedManager()->attach('File\Controller', 'dispatch', $authListener, 0);

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
                'File' =>  function() {
                    return new File();
                },
                'Files' =>  function() {
                    return new Files();
                },                
                'FileTable' =>  function($sm) {
                    $tableGateway = $sm->get('FileTableGateway');
                    $table = new FileTable($tableGateway);
                    return $table;
                },
                'FileTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new File());
                    return new TableGateway('files', $dbAdapter, null, $resultSetPrototype);
                },
                'FileFilter' =>  function($sm) {
                    return new FileFilter();
                },
                'FileService' =>  function($sm) {
                    $FileTable        = $sm->get('FileTable');
                    $LocationTable    = $sm->get('LocationTable');
                    $PageTable        = $sm->get('PageTable');
                    $ThumbnailTable   = $sm->get('ThumbnailTable');
                    $OrderedTable     = $sm->get('OrderedTable');
                    $MockupTable      = $sm->get('MockupTable');
                    return new FileService(
                        $FileTable,
                        $LocationTable, 
                        $PageTable, 
                        $ThumbnailTable, 
                        $OrderedTable, 
                        $MockupTable
                    );
                },
                'FileTableSlave' =>  function($sm) {
                    $tableMaster = $sm->get('FileTable');
                    $tableGateway = $sm->get('FileTableGatewaySlave');
                    $table = new FileTable($tableGateway, $tableMaster);
                    return $table;
                },
                'FileTableGatewaySlave' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_slave');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new File());
                    return new TableGateway('files', $dbAdapter, null, $resultSetPrototype);
                },
                'FileServiceSlave' =>  function($sm) {
                    $FileTable        = $sm->get('FileTableSlave');
                    $LocationTable    = $sm->get('LocationTableSlave');
                    $PageTable        = $sm->get('PageTableSlave');
                    $ThumbnailTable   = $sm->get('ThumbnailTableSlave');
                    $OrderedTable     = $sm->get('OrderedTableSlave');
                    $MockupTable      = $sm->get('MockupTableSlave');
                    return new FileService(
                        $FileTable,
                        $LocationTable,
                        $PageTable,
                        $ThumbnailTable,
                        $OrderedTable,
                        $MockupTable
                    );
                },
                'PurgeService' =>  function($sm) {
                    $FileTable        = $sm->get('FileTableSlave');
                    $LocationTable    = $sm->get('LocationTableSlave');
                    $PageTable        = $sm->get('PageTableSlave');
                    $ThumbnailTable   = $sm->get('ThumbnailTableSlave');
                    $OrderedTable     = $sm->get('OrderedTableSlave');
                    $MockupTable      = $sm->get('MockupTableSlave');
                    return new FileService(
                        $FileTable,
                        $LocationTable,
                        $PageTable,
                        $ThumbnailTable,
                        $OrderedTable,
                        $MockupTable
                    );
                },
                'TransferOwnershipFilter' =>  function() {
                    return new TransferOwnershipFilter();
                },
                'Location' =>  function() {
                    return new Location();
                },
                'LocationTable' =>  function($sm) {
                    $tableGateway = $sm->get('LocationTableGateway');
                    $table = new LocationTable($tableGateway);
                    return $table;
                },
                'LocationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Location());
                    return new TableGateway('locations', $dbAdapter, null, $resultSetPrototype);
                },
                'LocationFilter' =>  function() {
                    return new LocationFilter();
                },
                'LocationTableSlave' =>  function($sm) {
                    $tableMaster = $sm->get('LocationTable');
                    $tableGateway = $sm->get('LocationTableGatewaySlave');
                    $table = new LocationTable($tableGateway, $tableMaster);
                    return $table;
                },
                'LocationTableGatewaySlave' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_slave');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Location());
                    return new TableGateway('locations', $dbAdapter, null, $resultSetPrototype);
                },
                'OrderedTable' =>  function($sm) {
                    $tableGateway = $sm->get('OrderedTableGateway');
                    $table = new OrderedTable($tableGateway);
                    return $table;
                },
                'OrderedTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_master');
                    $Ordered = $sm->get('Ordered');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype($Ordered);
                    return new TableGateway('ordered', $dbAdapter, null, $resultSetPrototype);
                },
                'Ordered' =>  function($sm) {
                    return new Ordered();
                },
                'OrderedFilter' =>  function($sm) {
                    return new OrderedFilter();
                },
                'OrderedService' =>  function() {
                    return new OrderedService();
                },
                'OrderedTableSlave' =>  function($sm) {
                    $tableGateway = $sm->get('OrderedTableGatewaySlave');
                    $table = new OrderedTable($tableGateway);
                    return $table;
                },
                'OrderedTableGatewaySlave' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_slave');
                    $Ordered = $sm->get('Ordered');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype($Ordered);
                    return new TableGateway('ordered', $dbAdapter, null, $resultSetPrototype);
                },
                'Page' =>  function() {
                    return new Page();
                },
                'PageTable' =>  function($sm) {
                    $tableGateway = $sm->get('PageTableGateway');
                    $table = new PageTable($tableGateway);
                    return $table;
                },
                'PageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Page());
                    return new TableGateway('pages', $dbAdapter, null, $resultSetPrototype);
                },
                'PageService' =>  function() {
                    return new PageService();
                },
                'PageTableSlave' =>  function($sm) {
                    $tableGateway = $sm->get('PageTableGatewaySlave');
                    $table = new PageTable($tableGateway);
                    return $table;
                },
                'PageTableGatewaySlave' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_slave');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Page());
                    return new TableGateway('pages', $dbAdapter, null, $resultSetPrototype);
                },
                'PageFilter' =>  function() {
                    return new PageFilter();
                },
                'Thumbnail' =>  function() {
                    return new Thumbnail();
                },
                'ThumbnailTable' =>  function($sm) {
                    $tableGateway = $sm->get('ThumbnailTableGateway');
                    $table = new ThumbnailTable($tableGateway);
                    return $table;
                },
                'ThumbnailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Thumbnail());
                    return new TableGateway('thumbnails', $dbAdapter, null, $resultSetPrototype);
                },
                'ThumbnailService' =>  function() {
                    return new ThumbnailService();
                },
                'ThumbnailFilter' =>  function($sm) {
                    return new ThumbnailFilter();
                },
                'ThumbnailTableSlave' =>  function($sm) {
                    $tableGateway = $sm->get('ThumbnailTableGatewaySlave');
                    $table = new ThumbnailTable($tableGateway);
                    return $table;
                },
                'ThumbnailTableGatewaySlave' => function ($sm) {
                    $dbAdapter = $sm->get('files_db_slave');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Thumbnail());
                    return new TableGateway('thumbnails', $dbAdapter, null, $resultSetPrototype);
                },
                'Mockup' =>  function() {
                    return new Mockup();
                },
                'MockupTable' =>  function($sm) {
                    $tableGateway = $sm->get('MockupTableGateway');
                    $table = new MockupTable($tableGateway);
                    return $table;
                },
                'MockupTableGateway' => function ($sm) {
                    $config = $sm->get('Configuration');
                    $dbAdapter = $sm->get('files_db_master');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Mockup());
                    return new TableGateway('mockups', $dbAdapter, null, $resultSetPrototype);
                },
                'MockupService' =>  function() {
                    return new MockupService();
                },
                'MockupFilter' =>  function($sm) {
                    return new MockupFilter();
                },
                'MockupTableSlave' =>  function($sm) {
                    $tableGateway = $sm->get('MockupTableGatewaySlave');
                    $table = new MockupTable($tableGateway);
                    return $table;
                },
                'MockupTableGatewaySlave' => function ($sm) {
                    $config = $sm->get('Configuration');
                    $dbAdapter = $sm->get('files_db_slave');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Mockup());
                    return new TableGateway('mockups', $dbAdapter, null, $resultSetPrototype);
                },
                'Redis' => function ($sm) {
                    $config = $sm->get('Configuration');
                    return StorageFactory::factory ($config['redis']);
                },
                'StatusFilter' =>  function($sm) {
                    return new StatusFilter();
                },
            )
        );
    }

}
