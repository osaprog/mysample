<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Blog\Model\Entity\Blog;
use Blog\Model\BlogTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    /*public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }*/

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
    	return array(
    			'Zend\Loader\ClassMapAutoloader' => array(
    					__DIR__ . '/autoload_classmap.php',
    			),
    			'Zend\Loader\StandardAutoloader' => array(
    					'namespaces' => array(
    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /*public function getServiceConfig()
    {
        return array(
    			'factories' => array(
    					'Blog\Model\BlogTable' => function($sm) {
    					$tableGateway = $sm->get('BlogTableGateway');
    					$table = new BlogTable($tableGateway);
    					return $table;
                        },
                         'BlogTableGateway' => function ($sm) {
                         $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                         $resultSetPrototype = new ResultSet();
                         $resultSetPrototype->setArrayObjectPrototype(new Blog());
                         return new TableGateway('User_Posts', $dbAdapter, null, $resultSetPrototype);
                    },
                ),
            );
    }*/
    
    public function getServiceConfig() {
    	return array(
    			'factories' => array(
    					'Blog\Model\BlogTable' => function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$table = new Model\BlogTable($dbAdapter);
    						return $table;
    					},
    					'Blog\Model\CommentTable' => function($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$table = new Model\CommentTable($dbAdapter);
    						return $table;
    					},
    			),
    	);
    }
}
