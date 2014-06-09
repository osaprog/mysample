<?php
namespace BlogTest\Model;

use Blog\Model\Entity\Blog;
use PHPUnit_Framework_TestCase;

class BlogTest extends PHPUnit_Framework_TestCase
{
	public function testBlogInitialState()
	{
		$blog = new Blog();
 
		$this->assertNull(
				$blog->getContents(),
				'"_contents" should initially be null'
		);
		$this->assertNull(
				$blog->getId(),
				'"_id" should initially be null'
		);
		$this->assertNull(
				$blog->getTitle(),
				'"_title" should initially be null'
		);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
    $blog = new Blog();
    $data = array('_contents' => 'some _contents',
    		      '_id'=> 123,
    		      '_title' => 'some _title');
    
    		$blog->exchangeArray($data);
    
    		$this->assertSame(
    				$data['_contents'],
    				$blog->getContents(),
    				'"_contents" was not set correctly'
    		);
    		$this->assertSame(
    $data['_id'],
    $blog->getId(),
    '"_id" was not set correctly'
    );
    $this->assertSame(
    $data['_title'],
        $blog->getTitle(),
        '"_title" was not set correctly'
     
        );
     
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
    	$blog = new Blog();
    	$blog->exchangeArray(array('_contents' => 'some _contents',
    			'_id' => 123,
    			'_title' => 'some _title'));
    			$blog->exchangeArray(array());
    			$this->assertNull(
    					$blog->getContents(), '"_contents" should have defaulted to null'
    			);
    			$this->assertNull(
    					$blog->getId(), '"_id" should have defaulted to null'
    			);
    			$this->assertNull(
    					$blog->getTitle(), '"_title" should have defaulted to null'
    			);
    }
	public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
				{
				$blog = new Blog();
				$data = array('_contents' => 'some _contents',
				'_id' => 123,
				'_title' => 'some _title');

				$blog->exchangeArray($data);
				$copyArray = $blog->getArrayCopy();
				$this->assertSame(
						$data['_contents'],
						$copyArray['_contents'],
						'"_contents" was not set correctly'
				);
				$this->assertSame(
						$data['_id'],
						$copyArray['_id'],
    '"_id" was not set correctly'
    );
    $this->assertSame(
    $data['_title'],
    $copyArray['_title'],
    '"_title" was not set correctly'
    );
    
    }

    public function testInputFiltersAreSetCorrectly()
    {
    	$blog = new Blog();
    	$inputFilter = $blog->getInputFilter();
    	$this->assertSame(3, $inputFilter->count());
    	$this->assertTrue($inputFilter->has('_contents'));
    	$this->assertTrue($inputFilter->has('_id'));
    	$this->assertTrue($inputFilter->has('_title'));
    }
	
}
?>