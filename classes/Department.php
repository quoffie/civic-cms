<?php
/**
 * @copyright Copyright (C) 2006-2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Department extends ActiveRecord
{
	private $id;
	private $name;
	private $address1;
	private $address2;
	private $city;
	private $state;
	private $zip;
	private $phone;
	private $email;
	private $ldap_name;
	private $document_id;
	private $location_id;

	private $document;
	private $location;

	/**
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 */
	public function __construct($id=null)
	{
		if ($id)
		{
			$PDO = Database::getConnection();
			$sql = 'select * from departments where id=?';

			$query = $PDO->prepare($sql);
			$query->execute(array($id));

			$result = $query->fetchAll();
			if (!count($result)) { throw new Exception('departments/unknownDepartment'); }
			foreach($result[0] as $field=>$value) { if ($value) $this->$field = $value; }
		}
		else
		{
			# This is where the code goes to generate a new, empty instance.
			# Set any default values for properties that need it here
		}
	}


	/**
	 * This generates generic SQL that should work right away.
	 * You can replace this $fields code with your own custom SQL
	 * for each property of this class,
	 */
	public function save()
	{
		# Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->name) { throw new Exception('missingName'); }

		$fields = array();
		$fields['name'] = $this->name;
		$fields['address1'] = $this->address1 ? $this->address1 : null;
		$fields['address2'] = $this->address2 ? $this->address2 : null;
		$fields['city'] = $this->city ? $this->city : null;
		$fields['state'] = $this->state ? $this->state : null;
		$fields['zip'] = $this->zip ? $this->zip : null;
		$fields['phone'] = $this->phone ? $this->phone : null;
		$fields['email'] = $this->email ? $this->email : null;
		$fields['ldap_name'] = $this->ldap_name ? $this->ldap_name : null;
		$fields['document_id'] = $this->document_id ? $this->document_id : null;
		$fields['location_id'] = $this->location_id ? $this->location_id : null;

		# Split the fields up into a preparedFields array and a values array.
		# PDO->execute cannot take an associative array for values, so we have
		# to strip out the keys from $fields
		$preparedFields = array();
		foreach($fields as $key=>$value)
		{
			$preparedFields[] = "$key=?";
			$values[] = $value;
		}
		$preparedFields = implode(",",$preparedFields);


		if ($this->id) { $this->update($values,$preparedFields); }
		else { $this->insert($values,$preparedFields); }
	}

	private function update($values,$preparedFields)
	{
		$PDO = Database::getConnection();

		$sql = "update departments set $preparedFields where id={$this->id}";
		$query = $PDO->prepare($sql);
		$query->execute($values);
	}

	private function insert($values,$preparedFields)
	{
		$PDO = Database::getConnection();

		$sql = "insert departments set $preparedFields";
		$query = $PDO->prepare($sql);
		$query->execute($values);
		$this->id = $PDO->lastInsertID();
	}

	public function getUsers() { return new UserList(array('department_id'=>$this->id)); }

	public function __toString() { return $this->name; }

	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getAddress1() { return $this->address1; }
	public function getAddress2() { return $this->address2; }
	public function getCity() { return $this->city; }
	public function getState() { return $this->state; }
	public function getZip() { return $this->zip; }
	public function getPhone() { return $this->phone; }
	public function getEmail() { return $this->email; }
	public function getLdap_name() { return $this->ldap_name; }
	public function getDocument_id() { return $this->document_id; }
	public function getLocation_id() { return $this->location_id; }
	public function getDocument()
	{
		if ($this->document_id)
		{
			if (!$this->document) { $this->document = new Document($this->document_id); }
			return $this->document;
		}
		else return null;
	}
	public function getLocation()
	{
		if ($this->location_id)
		{
			if (!$this->location) { $this->location = new Location($this->location_id); }
			return $this->location;
		}
		else return null;
	}


	public function setName($string) { $this->name = trim($string); }
	public function setAddress1($string) { $this->address1 = trim($string); }
	public function setAddress2($string) { $this->address2 = trim($string); }
	public function setCity($string) { $this->city = trim($string); }
	public function setState($string) { $this->state = trim($string); }
	public function setZip($string) { $this->zip = trim($string); }
	public function setPhone($string) { $this->phone = trim($string); }
	public function setEmail($string) { $this->email = trim($string); }
	public function setLdap_name($string) { $this->ldap_name = trim($string); }
	public function setLocation_id($int) { $this->location = new Location($int); $this->location_id = $int; }
	public function setLocation($location) { $this->location_id = $location->getId(); $this->location = $location; }
	public function setDocument_id($int)
	{
		# A document can only be set as the homepage for a department, if that
		# document is also owned by that department
		if ($int)
		{
			$document = new Document($int);
			if ($document->getDepartment_id() == $this->id)
			{
				$this->document = new Document($int);
				$this->document_id = $int;
			}
			else { throw new Exception('departments/documentDepartmentMismatch'); }
		}
	}
	public function setDocument($document)
	{
		# A document can only be set as the homepage for a department, if that
		# document is also owned by that department
		if ($document->getDepartment_id() == $this->id)
		{
			$this->document_id = $document->getId();
			$this->document = $document;
		}
		else { throw new Exception('departments/documentDepartmentMismatch'); }
	}
}
