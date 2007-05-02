<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="tabBar">
<ul><li><a href="<?php echo BASE_URL; ?>/documents/updateDocument.php?tab=info" onclick="processTabbedForm('documentForm','info','next');return false;">Info</a></li>
	<li><a href="<?php echo BASE_URL; ?>/documents/updateDocument.php?tab=content" onclick="processTabbedForm('documentForm','content','next');return false;">Content</a></li>
	<li><a href="<?php echo BASE_URL; ?>/documents/updateDocument.php?tab=sections" onclick="processTabbedForm('documentForm','sections','next');return false;">Sections</a></li>
	<li><a href="<?php echo BASE_URL; ?>/documents/updateDocument.php?tab=attachments" onclick="processTabbedForm('documentForm','attachments','next');return false;">Attachments</a></li>
	<li><a href="<?php echo BASE_URL; ?>/documents/updateDocument.php?tab=facets" onclick="processTabbedForm('documentForm','facets','next');return false;">Facets</a></li>
	<?php
		if(userHasRole('Webmaster'))
		{
			echo "<li><a href=\"".BASE_URL."/documents/updateDocument.php?tab=facets\" onclick=\"processTabbedForm('documentForm','source','next');return false;\">Source</a></li>";
		}
	?>
</ul>
</div>