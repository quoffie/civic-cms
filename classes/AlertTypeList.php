<?php
/**
 * @copyright Copyright (C) 2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
class AlertTypeList extends PDOResultIterator
{
	public function __construct($fields=null)
	{
		$this->select = 'select alertTypes.id as id from alertTypes';
		if (is_array($fields)) $this->find($fields);
	}

	public function find($fields=null,$sort='id',$limit=null,$groupBy=null)
	{
		$this->sort = $sort;
		$this->limit = $limit;
		$this->groupBy = $groupBy;

		$options = array();
		$parameters = array();
		if (isset($fields['id']))
		{
			$options[] = "id=:id";
			$parameters[':id'] = $fields['id'];
		}
		if (isset($fields['name']))
		{
			$options[] = "name=:name";
			$parameters[':name'] = $fields['name'];
		}

		# Finding on fields from other tables required joining those tables.
		# You can add fields from other tables to $options by adding the join SQL
		# to $this->joins here

		$this->populateList($options,$parameters);
	}

	protected function loadResult($key) { return new AlertType($this->list[$key]); }
}
