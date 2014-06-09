<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $serviceManager = $e->getApplication()->getServiceManager();
        // session container
        $sessionContainer = new \Zend\Session\Container('session_container');
        
        // test if session language exists
        if(!$sessionContainer->offsetExists('mylocale')){
        	// if not use the browser locale
        	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
        		$sessionContainer->offsetSet('mylocale', \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']));
        	}else{
        		$sessionContainer->offsetSet('mylocale', 'en_US');
        	}
        
        }
        
        // translating system
        $translator = $serviceManager->get('translator');
        $translator ->setLocale($sessionContainer->mylocale)
        ->setFallbackLocale('en_US');
        
        $mylocale = $sessionContainer->mylocale;
        
        
        // Assign locale to value layout.phtml view, to make selected language active
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->mylocale = $mylocale;
        
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
