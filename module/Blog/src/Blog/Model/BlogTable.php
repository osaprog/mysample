<?php
namespace Blog\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Debug\Debug;

class BlogTable extends AbstractTableGateway
{
    protected $table = 'Blogs';
    
	public function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll() {
		$resultSet = $this->select(function (Select $select) {
			$select->order('CreatedOn ASC');
		});
		$entities = array();
		foreach ($resultSet as $row) {
			$entity = new Entity\Blog();
			$entity->setId($row->Id)
			->setTitle($row->Title)
			->setContents($row->Contents);
			$entities[] = $entity;
		}
		return $entities;
	}

	public function getBlog($id) {
		$row = $this->select(array('Id' => (int) $id))->current();
		if (!$row)
			return false;
	
		$blog = new Entity\Blog(array(
				'Id' => $row->Id,
				'Title' => $row->Title,
				'Contents' => $row->Contents,
		));
		return $blog;
	}

 
	
	public function saveBlog(Entity\Blog $blog) {
		$data = array(
				'Title' => $blog->getTitle(),
				'Contents' => $blog->getContents(),
		);
	
		$id = (int) $blog->getId();
	
		if ($id == 0) {
			$data['CreatedOn'] = date("Y-m-d H:i:s");
			if (!$this->insert($data))
				return false;
				//throw new \Exception('Blog id does not exist');
			return $this->getLastInsertValue();
		}
		elseif ($this->getBlog($id)) {

			if (!$this->update($data, array('Id' => $id)))
				return false;
			    //throw new \Exception('Blog id does not exist');
			return $id;
		}
		else
			//throw new \Exception('Blog id does not exist');
			return false;
	}
		
	public function deleteBlog($id)
	{
		$this->delete(array('Id' => $id));
	}
}