<?php
namespace Blog\Model\Entity;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Debug\Debug;

class Comment implements InputFilterAwareInterface
{
protected $_id;
protected $_text;
protected $_blogid;
protected $inputFilter;

    public function __construct(array $options = null) {
    	if (is_array($options)) {
    		$this->setOptions($options);
    	}
    }
    
    public function __set($name, $value) {
    	$method = 'set' . $name;
    	if (!method_exists($this, $method)) {
    		throw new \Exception('Invalid Method');
    	}
    	$this->$method($value);
    }
    
    public function __get($name) {
    	$method = 'get' . $name;
    	if (!method_exists($this, $method)) {
    		throw new \Exception('Invalid Method');
    	}
    	return $this->$method();
    }
    

    public function getId() {
    	return $this->_id;
    }
    
    public function setId($id) {
    	$this->_id = $id;
    	return $this;
    }
    
    public function getText() {
    	return $this->_text;
    }
    
    public function setText($text) {
    	$this->_text = $text;
    	return $this;
    }

    public function getBlogId() {
    	return $this->_blogid;
    }
    
    public function setBlogId($blogid) {
    	$this->_blogid = $blogid;
    	return $this;
    }

    public function setOptions(array $options) {
    	$methods = get_class_methods($this);
    	foreach ($options as $key => $value) {
    		$method = 'set' . ucfirst($key);
    		if (in_array($method, $methods)) {
    			$this->$method($value);
    		}
    	}
    	return $this;
    }
    
    public function exchangeArray($data)
    {

        if (is_array($data)) {
        	$this->setOptions($data);
        }

    }

    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    

    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    
    		$inputFilter->add(array(
    				'name'=> '_id',
    				'required' => true,
    				'filters' => array(
                            array('name' => 'Int'),
                        ),
                        )
    				);
    		
    		$inputFilter->add(array(
    				'name' => '_text',
    				'required' => true,
    				'filters' => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    								'name' => 'StringLength',
    								'options' => array(
                            		'encoding' => 'UTF-8',
                            		'min'=> 1,
                            		'max'=> 100,
                            		),
    		        ),
    		    ),
    		));
    		
    		$inputFilter->add(array(
    				'name'=> '_blogid',
    				'required' => true,
    				'filters' => array(
                            array('name' => 'Int'),
                        ),
                        )
    				);
    
    		$this->inputFilter = $inputFilter;
    		}
    		
    	return $this->inputFilter;
    	}
}
?>