<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	class LanguageList extends PDOResultIterator
	{

		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select languages.code as id from languages";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}
	

		public function find($fields=null,$sort="code")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['code'])) { $options[] = "code='$fields[code]'"; }
			if (isset($fields['english'])) { $options[] = "english='$fields[english]'"; }
			if (isset($fields['native'])) { $options[] = "native='$fields[native]'"; }


			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to $options by adding the join SQL
			# to $this->joins here

			$this->populateList($options);
		}
	

		protected function loadResult($key) { return new Language($this->list[$key]); }
	}
?>