<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'controllers' => array(
				'invokables' => array(
						'Blog\Controller\Blog' => 'Blog\Controller\BlogController',
				),
		),
    
    'router' => array(
    		'routes' => array(
    				'blog' => array(
    						'type' => 'segment',
    						'options' => array(
    							'route'=> '/blog[/][:action][/:id]',
    						    'constraints' => array(
    						    		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
    						    		'id' => '[0-9]+',
    						    ),
    						    'defaults' => array(
    						    		'controller' => 'Blog\Controller\Blog',
    						    		'action' => 'index',
    						  ),
    			     ),
    						     
    		    ),
    						     
    		 ),
    						     
        ),
    						    
	'view_manager' => array(
				'template_path_stack' => array(
        'blog' => __DIR__ . '/../view',
    ),
   ),
);