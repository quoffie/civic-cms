<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	verifyUser('Webmaster');

	if (isset($_POST['location']))
	{
		$location = new Location();
		foreach($_POST['location'] as $field=>$value)
		{
			$set = 'set'.ucfirst($field);
			$location->$set($value);
		}

		try
		{
			$location->save();
			Header('Location: home.php');
			exit();
		}
		catch(Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$template = new Template();
	$template->blocks[] = new Block('locations/addLocationForm.inc');
	$template->render();
?>