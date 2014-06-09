<?php
namespace BlogTest\Controller;

require_once  realpath( 'module/Blog/test/Bootstrap.php');

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
	public function setUp()
	{
		$this->setApplicationConfig(
				include   realpath('config/application.config.php')
		);
		
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        /**************/
        $blogTableMock = $this->getMockBuilder('Blog\Model\BlogTable')
        ->disableOriginalConstructor()
        ->getMock();
        
        $blogTableMock->expects($this->once())
        ->method('fetchAll')
        ->will($this->returnValue(array()));
 
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Blog\Model\BlogTable', $blogTableMock);
        /**************/
        
    	$this->dispatch('/blog');
    	$this->assertResponseStatusCode(200);
    	$this->assertModuleName('Blog');
    	$this->assertControllerName('Blog\Controller\Blog');
    	$this->assertControllerClass('BlogController');
    	$this->assertMatchedRouteName('blog');
    }
    
    public function testAddActionRedirectsAfterValidPost()
    {
    	$blogTableMock = $this->getMockBuilder('Blog\Model\BlogTable')
    	->disableOriginalConstructor()
    	->getMock();
    	$blogTableMock->expects($this->once())
    	->method('saveBlog')
    	->will($this->returnValue(null));
    	$serviceManager = $this->getApplicationServiceLocator();
    	$serviceManager->setAllowOverride(true);
    	$serviceManager->setService('Blog\Model\BlogTable', $blogTableMock);
    	$postData = array(
    			'_title' => 'Blog Title',
    			'_contents' => 'Blog Content',
    	);
    	$this->dispatch('/blog/add', 'POST', $postData);
    	$this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/blog/');
    }
}
?>