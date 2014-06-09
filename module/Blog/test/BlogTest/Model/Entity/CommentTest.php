<?php
namespace BlogTest\Model;

use Blog\Model\Entity\Comment;
use PHPUnit_Framework_TestCase;
use Zend\Debug\Debug;

class CommentTest extends PHPUnit_Framework_TestCase
{
	public function testCommentInitialState()
	{
	    $data = array('BlogId' => 1);
		$comment = new Comment($data);
 
		$this->assertNull(
				$comment->getText(),
				'"text" should initially be null'
		);
		$this->assertNull(
				$comment->getId(),
				'"_id" should initially be null'
		);
		
		$this->assertTrue(
				is_int($comment->getBlogId()),
				'"_blogid" should initially be int'
		);
		
 		
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
    $comment = new Comment();
    $data = array('BlogId' => 2,
    		      'Id'=> 123,
    		      'Text' => 'some _text');
     
    		$comment->exchangeArray($data);

    		$this->assertSame(
    				$data['BlogId'],
    				$comment->getBlogId(),
    				'"BlogId" was not set correctly'
    		);
    		
    		$this->assertSame(
                $data['Id'],
                $comment->getId(),
                '"Id" was not set correctly'
                );
    		
            $this->assertSame(
            $data['Text'],
                $comment->getText(),
                '"Text" was not set correctly'
             
                );
     
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
    	$comment = new Comment();
    	       $comment->exchangeArray(array('_blogid' => 2,
    			'_id' => 123,
    			'_text' => 'some _text'));
    	       
    			$comment->exchangeArray(array());
    			$this->assertNull(
    					$comment->getText(), '"_text" should have defaulted to null'
    			);
    			
    			$this->assertNull(
    					$comment->getId(), '"_id" should have defaulted to null'
    			);
    			
    			$this->assertNull(
    					$comment->getBlogId(), '"_blogid" should have defaulted to null'
    			);
    }
	public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
				{
				$comment = new Comment();
				$data = array('Text' => 'some _text',
				'Id' => 123,
				'BlogId' => 2);

				$comment->exchangeArray($data);
				

				$copyArray = $comment->getArrayCopy();
 
				$this->assertSame(
						$data['Text'],
						$copyArray['_text'],
						'"Text" was not set correctly'
				);
				
				$this->assertSame(
						$data['Id'],
						$copyArray['_id'],
                '"Id" was not set correctly'
                );
				
                $this->assertSame(
                $data['BlogId'],
                $copyArray['_blogid'],
                '"BlogId" was not set correctly'
                );
    
    }

    public function testInputFiltersAreSetCorrectly()
    {
    	$comment = new Comment();
    	$inputFilter = $comment->getInputFilter();
    	$this->assertSame(3, $inputFilter->count());
    	$this->assertTrue($inputFilter->has('_blogid'));
    	$this->assertTrue($inputFilter->has('_id'));
    	$this->assertTrue($inputFilter->has('_text'));
    }
	
}
?>