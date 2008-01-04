<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET return_url
 */
verifyUser(array('Administrator','Webmaster','Content Creator'));

if (isset($_REQUEST['location_id']))
{
	$location = new Location($_REQUEST['location_id']);

	# Make sure they're allowed to edit this Location
	if (!$location->permitsEditingBy($_SESSION['USER']))
	{
		$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
		Header('Location: home.php');
		exit();
	}
}
# We're creating a new Location
else { $location = new Location(); }


if (isset($_POST['location']))
{
	# Make sure they're allowed to edit this location
	if (!$location->permitsEditingBy($_SESSION['USER']))
	{
		$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
		Header('Location: home.php');
		exit();
	}

	foreach($_POST['location'] as $field=>$value)
	{
		$set = 'set'.ucfirst($field);
		$location->$set($value);
	}

	# Departments should only be changeable by Webmaster/Admin
	if (isset($_POST['department_id']) && userHasRole(array('Administrator','Webmaster')))
	{
		$location->setDepartment_id($_POST['department_id']);
	}

	# Directions will come in from the WYSIWYG editor
	if (isset($_POST['content'])) { $location->setContent($_POST['content']); }

	try
	{
		$location->save();
		Header("Location: $_POST[return_url]");
		exit();
	}
	catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
}

$form = new Block('locations/updateLocationForm.inc');
$form->location = $location;
$form->return_url = $_REQUEST['return_url'];

$template = new Template();
$template->blocks[] = $form;
$template->render();
