<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Entity\Blog;
use Blog\Form\BlogForm;
use Zend\Debug\Debug;

class BlogController extends AbstractActionController

{
    protected $blogTable;
    protected $commentTable;

    public function indexAction()
    {
        return new ViewModel(array(
        		'blogs' => $this->getBlogTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new BlogForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$blog = new Blog();        	
        	$form->setInputFilter($blog->getInputFilter());
        	$form->setData($request->getPost());
        	
        	if ($form->isValid()) {
        	    
        	    $blog->exchangeArray($form->getData());
        		$this->getBlogTable()->saveBlog($blog);
        		// Redirect to list of blogs
        		return $this->redirect()->toRoute('blog');
        		
        	}
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
        	return $this->redirect()->toRoute('blog', array(
        			'action' => 'add'
        	));
        }
        // Get the Blog with the specified id. An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
        	$blog = $this->getBlogTable()->getBlog($id);

        }
        catch (\Exception $ex) {
        	return $this->redirect()->toRoute('blog', array(
        			'action' => 'index'
        	));
        }
        
        $form = new BlogForm();
        $form->bind($blog);
        $form->get('submit')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
        

        if ($request->isPost()) {
            
        	$form->setInputFilter($blog->getInputFilter());
        	$form->setData($request->getPost());
            
        	
        	if ($form->isValid()) {
        	    
        	    $post_data = $request->getPost();
        	    $_id = $post_data['_id'];
        	    $_title = $post_data['_title'];
        	    $_contents = $post_data['_contents'];
        	    //$stickynote = $this->getBlogTable()->getBlog($_id);
        	    $blog->setContents($_contents);
        	    $blog->setTitle($_title);
        		$this->getBlogTable()->saveBlog($blog);
        	
        		// Redirect to list of blogs
        		return $this->redirect()->toRoute('blog');
        	}
        	
        }
        
        return array(
        		'id' => $id,
        		'form' => $form,
        );
    }
    
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
        	return $this->redirect()->toRoute('blog');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            if ($request->getPost('del')) {
          		$id = (int) $request->getPost('id');
        		$this->getBlogTable()->deleteBlog($id);
        		$this->getCommentTable()->deleteCommentsByBlogId($id);
        	}
        	
        	// Redirect to list of blogs
        	return $this->redirect()->toRoute('blog');        	
        }
        return array(
        		'id' => $id,
        		'blog' => $this->getBlogTable()->getBlog($id)
        );
    }

    public function getBlogTable()
    {
    	if (!$this->blogTable) {
    		$sm = $this->getServiceLocator();
    		$this->blogTable = $sm->get('Blog\Model\BlogTable');
    	}
    	return $this->blogTable;
    }
    
    public function viewAction(){
    	$id = (int) $this->params()->fromRoute('id', 0);

    	return array(
    			'id' => $id,
    			'blog' => $this->getBlogTable()->getBlog($id),
    	        'comments' => $this->getCommentTable()->fetchAll($id)
    	);
    	 
    }

    // Comments
    
    public function addcommentAction() {
 
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	if ($request->isPost()) {
    	    $post_data = $request->getPost();
    	    $blog_id = $post_data['blogid'];
    	    $data = array('BlogId'=>$blog_id);

    		$new_comment = new \Blog\Model\Entity\Comment($data);

    		if (!$comment_id = $this->getCommentTable()->saveComment($new_comment))
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
    		else {
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'new_comment_id' => $comment_id)));
    		}
    	}
    	return $response;
    }
    
    public function removecommentAction() {
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	if ($request->isPost()) {
    		$post_data = $request->getPost();
    		$comment_id = $post_data['id'];

    		if (!$this->getCommentTable()->deleteComment($comment_id))
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
    		else {
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
    		}
    	}
    	return $response;
    }
    
    public function updatecommentAction() {
    	// update post
    	$request = $this->getRequest();
    	$response = $this->getResponse();
    	if ($request->isPost()) {
    		$post_data = $request->getPost();
    		$comment_id = $post_data['id'];
    		$comment_text = $post_data['content'];
    		$comment = $this->getCommentTable()->getComment($comment_id);

    		$comment->setText($comment_text);
    		if (!$this->getCommentTable()->saveComment($comment))
    			$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
    		else {
    			$response->setContent(\Zend\Json\Json::encode(array('response' => true)));
    		}
    	}
    	return $response;
    }
    
    public function getCommentTable()
    {
    	if (!$this->commentTable) {
    		$sm = $this->getServiceLocator();
    		$this->commentTable = $sm->get('Blog\Model\CommentTable');
    	}
    	return $this->commentTable;
    }
    
    public function changelangAction(){
    
    	//$mylocale = new Container('mylocale');
    	$sessionContainer = new \Zend\Session\Container('session_container');
    
    	$id = (int) $this->params()->fromRoute('id', 1);
    
    	switch ($id){
    		case '1':
    			$mylocale = 'ar_SY';
    			break;
    		case '2':
    			$mylocale = 'en_US';
    			break;
    		case '3':
    			$mylocale = 'de_DE';
    			break;
    	}
    	$sessionContainer->offsetSet('mylocale', $mylocale);
    	 
    
    	if (!empty($sessionContainer->current_page)){
    		return $this->redirect()->toUrl($sessionContainer->current_page);
    	}else{
    		return $this->redirect()->toRoute('home' );
    	}
    
    }
    
}