<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET search
 */
	$template = new Template();
	if (isset($_GET['search']) && $_GET['search'])
	{
		$search = new Search();
		$results = $search->find($_GET['search']);

		$template->blocks[] = new Block('documents/searchResults.inc',array('results'=>$results));
	}
	$template->render();
?>