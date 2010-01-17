<?php

class Default_Model_Ticket
{
		/* Fields */
		protected $_id;
		protected $_project_id;
		protected $_creation_date;
		protected $_creator_id;
		protected $_modification_date;
		protected $_modificator_id;
		protected $_title;
		protected $_description;
		protected $_milestone_id;
		protected $_type;
		protected $_priority;
		protected $_status;
		protected $_assigned_to_id;

		/* Relations */
		protected $_milestone;
		protected $_creator;
		protected $_modificator;
		protected $_assigned_to;

    static public function getMapper()
    {
      return Default_Model_TicketsMapper::getInstance();
    }

    public function __construct($row = null)
    {
			if ($row instanceof Zend_Db_Table_Row)
				$this->_initWithRow($row);
			elseif ($row !== null)
				$this->_initNew($row);
		}

    protected function _initNew(array $values)
    {
    	if (isset($values['project_id']))
				$this->_project_id = $values['project_id'];
			$this->_creation_date = new Zend_Date((isset($values['creation_date']) ? $values['creation_date'] : null));
    	if (isset($values['creator_id']))
				$this->_creator_id = $values['creator_id'];
  		if (isset($values['modificator_id']) && isset($values['modification_date']))
  		{
				$this->_modification_date = new Zend_Date($values['modification_date']);
				$this->_modificator_id = $values['modificator_id'];
  		}
  		else
  		{
	  		$this->_modification_date = $this->_creation_date;
				$this->_modificator_id = $this->_creator_id;
			}
    	if (isset($values['title']))
				$this->_title = $values['title'];
    	if (isset($values['description']))
				$this->_description = $values['description'];
    	if (isset($values['milestone_id']))
				$this->_milestone_id = $values['milestone_id'];
    	if (isset($values['type']))
				$this->_type = $values['type'];
    	if (isset($values['priority']))
				$this->_priority = $values['priority'];
    	if (isset($values['status']))
				$this->_status = $values['status'];
			if (isset($values['assigned_to_id']))
				$this->_assigned_to_id = $values['assigned_to_id'];
    }

    protected function _initWithRow(Zend_Db_Table_Row $row)
		{
			$this->_id = $row->ticket_id;
			$this->_creation_date = new Zend_Date($row->creation_date);
			$this->_creator_id = $row->creator_id;
			$this->_modification_date = new Zend_Date($row->modification_date);
			$this->_modificator_id = $row->modificator_id;
			$this->_project_id = $row->project_id;
			$this->_title = $row->title;
			$this->_description = $row->description;
			$this->_milestone_id = $row->milestone_id;
			$this->_type = $row->type;
			$this->_priority = $row->priority;
			$this->_status = $row->status;
			$this->_assigned_to_id = $row->assigned_to_id;
    }

    public function updateWithValues($values)
    {
      if (!empty($values['project_id']))
      	$this->_project_id = $values['project_id'];
      if (!empty($values['modificator_id']))
      {
      	$this->_modification_date = new Zend_Date(null);
      	$this->_modificator_id = $values['modificator_id'];
      }
      else
      {
        $this->_modification_date = null;
        $this->_modificator_id = null;
      }
      if (!empty($values['title']))
      	$this->_title = $values['title'];
      if (!empty($values['description']))
      	$this->_description = $values['description'];
      if (!empty($values['milestone_id']))
      	$this->_milestone_id = $values['milestone_id'];
      if (!empty($values['type']))
      	$this->_type = $values['type'];
      if (!empty($values['priority']))
      	$this->_priority = $values['priority'];
      if (!empty($values['status']))
      	$this->_status = $values['status'];
      if (isset($values['assigned_to_id']) && $values['assigned_to_id'] !== null)
      	$this->_assigned_to_id = ($values['assigned_to_id'] == 0 ? null : $values['assigned_to_id']);
    }

		public function __set($name, $value)
		{
			$setter = 'set' . ucfirst($name);
			if (!method_exists($this, $setter))
			{
				throw new Exception('Try to set an invalid property "' . $name . '" for class "' . get_class($this) . '"');
			}
			$this->$setter($value);
		}

		public function __get($name)
		{
			$getter = 'get' . ucfirst($name);
			if (('mapper' == $name) || !method_exists($this, $getter))
			{
				throw new Exception('Try to get an invalid property "' . $name . '" for class "' . get_class($this) . '"');
			}
			return $this->$getter();
		}

		/**
		 * return an array with an error for each invalid field
		 * if return is empty => good to save
		 */
		public function validate()
		{
			return array();
		}

		public function isNew()
		{
			return empty($this->_id);
		}

		public function getId()
		{
			return $this->_id;
		}

		public function setProjectId($txt)
		{
			$this->_project_id = (string) $txt;
			return $this;
		}

		public function getProjectId()
		{
			return $this->_project_id;
		}

		public function setCreationDate($txt)
		{
			$this->_creation_date = (string) $txt;
			return $this;
		}

		public function getCreationDate()
		{
			return $this->_creation_date;
		}

		public function setCreatorId($txt)
		{
			$this->_creator_id = (string) $txt;
			return $this;
		}

		public function getCreatorId()
		{
			return $this->_creator_id;
		}

    public function getCreator()
    {
      if (empty($this->_creator_id))
        return null;
      if ($this->_creator === null || $this->_creator_id !== $this->_creator->users_id)
      {
        $table = new USVN_Db_Table_Users();
        $this->_creator = $table->fetchRow(array('users_id = ?' => $this->_creator_id));
      }
      return $this->_creator;
    }

		public function setModificationDate($txt)
		{
			$this->_modification_date = (string) $txt;
			return $this;
		}

		public function getModificationDate()
		{
			return $this->_modification_date;
		}

		public function setModificatorId($txt)
		{
			$this->_modificator_id = (string) $txt;
			return $this;
		}

		public function getModificatorId()
		{
			return $this->_modificator_id;
		}

    public function getModificator()
    {
      if (empty($this->_modificator_id))
        return null;
      if ($this->_modificator === null || $this->_modificator_id !== $this->_modificator->users_id)
      {
        $table = new USVN_Db_Table_Users();
        $this->_modificator = $table->fetchRow(array('users_id = ?' => $this->_modificator_id));
      }
      return $this->_modificator;
    }

		public function setTitle($txt)
		{
			$this->_title = (string) $txt;
			return $this;
		}

		public function getTitle()
		{
			return $this->_title;
		}

		public function setDescription($txt)
		{
			$this->_description = (string) $txt;
			return $this;
		}

		public function getDescription()
		{
			return $this->_description;
		}

		public function setMilestoneId($txt)
		{
			$this->_milestone_id = (string) $txt;
			return $this;
		}

		public function getMilestoneId()
		{
			return $this->_milestone_id;
		}
		
		public function getMilestone()
		{
			if ($this->_milestone_id === null)
				return null;
			if ($this->_milestone === null)
				$this->_milestone = Default_Model_Milestone::find($this->_milestone_id);
			return $this->_milestone;
		}
		
		public function setType($txt)
		{
			$this->_type = (string) $txt;
			return $this;
		}

		public function getType()
		{
			return $this->_type;
		}
		
		static public function types()
		{
		  return array(
		    0 => 'new feature',
		    1 => 'improvement',
		    2 => 'bug',
		    3 => 'other');
		}

		public function getTypeText()
		{
		  $t = self::types();
		  if (array_key_exists($this->_type, $t))
		    return $t[$this->_type];
		  return $this->_type;
		}

		public function setPriority($txt)
		{
			$this->_priority = (string) $txt;
			return $this;
		}

		public function getPriority()
		{
			return $this->_priority;
		}

		public function getPriorityImage()
		{
		  $imgs = array(
		    2 => 'red-priority.png',
		    1 => 'yellow-priority.png',
		    0 => 'green-priority.png',
		    -1 => 'blue-priority.png'
		    );
		    if (array_key_exists($this->_priority, $imgs))
		      return $imgs[$this->_priority];
		    return 'blue-priority.png';
		}

    static public function priorities()
    {
      return array(
        2 => 'Urgent',
        1 => 'Important',
        0 => 'Normal',
        -1 => 'Secondary'
        );
    }
		
		public function getPriorityText()
		{
		  $p = self::priorities();
		  if (array_key_exists($this->_priority, $p))
		    return $p[$this->_priority];
		  return $this->_priority;
		}

		public function setStatus($txt)
		{
			$this->_status = $txt;
			return $this;
		}

		public function getStatus()
		{
			return $this->_status;
		}

    public function getStatusText()
    {
      $s = self::statuses();
      if (array_key_exists($this->_status, $s))
        return $s[$this->_status];
      return $this->_status;
    }

    static public function statuses()
    {
      return array(
        0 => 'Open',
        1 => 'Resolved',
        2 => 'Close'
        );
    }

		public function setAssignedToId($userId)
		{
			$this->_assigned_to_id = $userId;
			return $this;
		}

		public function getAssignedToId()
		{
			return $this->_assigned_to_id;
		}

    public function getAssignedTo()
    {
      if (empty($this->_assigned_to_id))
        return null;
      if ($this->_assigned_to === null || $this->_assigned_to_id !== $this->_assigned_to->users_id)
      {
        $table = new USVN_Db_Table_Users();
        $this->_assigned_to = $table->fetchRow(array('users_id = ?' => $this->_assigned_to_id));
      }
    	return $this->_assigned_to;
    }

		public function save()
		{
			$newId = $this->getMapper()->save($this);
			if ($newId === null || $newId === false)
				return false;
			else if ($this->_id === null)
  			$this->_id = $newId;
			return true;
		}
		
		public function delete()
		{
			return $this->getMapper()->delete($this);
		}

		static public function deleteId($id)
		{
			return self::getMapper()->deleteId($id);
		}

		static public function find($id)
		{
			return self::getMapper()->find($id);
		}

		static public function fetchAll($where = null, $order = null)
		{
			return self::getMapper()->fetchAll($where, $order);
		}
		
		static public function fetchRow($where = null, $order = null)
		{
			return self::getMapper()->fetchRow($where, $order);
		}
}
