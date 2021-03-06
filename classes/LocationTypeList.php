<?php
/**
 * @copyright Copyright (C) 2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class LocationTypeList extends PDOResultIterator
{
	public function __construct($fields=null)
	{
		$this->select = 'select locationTypes.id as id from locationTypes';
		if (is_array($fields)) $this->find($fields);
	}

	public function find($fields=null,$sort='type',$limit=null,$groupBy=null)
	{
		$this->sort = $sort;
		$this->limit = $limit;
		$this->groupBy = $groupBy;
		$this->joins = '';

		$options = array();
		$parameters = array();
		if (isset($fields['id']))
		{
			$options[] = 'id=:id';
			$parameters[':id'] = $fields['id'];
		}
		if (isset($fields['type']))
		{
			$options[] = 'type=:type';
			$parameters[':type'] = $fields['type'];
		}


		# Finding on fields from other tables required joining those tables.
		# You can add fields from other tables to $options by adding the join SQL
		# to $this->joins here

		$this->populateList($options,$parameters);
	}

	protected function loadResult($key) { return new LocationType($this->list[$key]); }
}
