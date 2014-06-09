<?php
namespace Blog\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Debug\Debug;

class CommentTable extends AbstractTableGateway
{
    protected $table = 'Comments';
    
	public function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll($blogid) {
	    

		/*$resultSet = $this->select(function (Select $select) {
			$select->order('CreatedOn ASC');
		});*/
		
        $resultSet = $this->select(array('BlogId'=>(int)$blogid));
        
		$entities = array();
		foreach ($resultSet as $row) {
			$entity = new Entity\Comment();
			$entity->setId($row->Id)
			->setBlogId($row->BlogId)
			->setText($row->Text);
			$entities[] = $entity;
		}
		return $entities;
	}

	public function getComment($id) {
		$row = $this->select(array('Id' => (int) $id))->current();
		if (!$row)
			return false;

		$comment = new Entity\Comment(array(
				'Id' => $row->Id,
				'BlogId' => $row->BlogId,
				'Text' => $row->Text,
		));

		return $comment;
	}
	
	public function saveComment(Entity\Comment $comment) {
		$data = array(
				'BlogId' => $comment->getBlogId(),
				'Text' => $comment->getText(),
		);
	
		$id = (int) $comment->getId();
	
		if ($id == 0) {
			$data['CreatedOn'] = date("Y-m-d H:i:s");
			if (!$this->insert($data))
				return false;
			return $this->getLastInsertValue();
		}
		elseif ($this->getComment($id)) {

			if (!$this->update($data, array('Id' => $id)))
				return false;
			return $id;
		}
		else
			return false;
	}
		
	public function deleteComment($id)
	{  
		return $this->delete(array('Id' => $id));
	}
	
	public function deleteCommentsByBlogId($blogid){
	    return $this->delete(array('BlogId' => $blogid));
	    
	}
}