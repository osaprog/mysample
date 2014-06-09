<?php
namespace Blog\Form;

use Zend\Form\Form;
class BlogForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('blog');
		
    		$this->add(array(
    				'name' => '_id',
    				'type' => 'Hidden',
    		));
    		$this->add(array(
            		'name' => '_title',
            		'type' => 'Text',
            		'options' => array(
            		  'label' => 'Title',
    		         ),
    		));
    		$this->add(array(
    				'name' => '_contents',
    				'type' => 'textarea',
                    'options' => array(
                        'label' => 'Contents',
                        ),
                ));
             $this->add(array(
                    'name' => 'submit',
                    'type' => 'Submit',
                    'attributes' => array(
                    		'value' => 'Go',
                    		'id' => 'submitbutton',
                    ),
        ));
        
        }
        
}