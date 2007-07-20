<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param FacetGroup $this->facetGroup
 */
?>
<h1>Update Facet Group</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Facet Group Info</legend>
	<input name="facetGroup_id" type="hidden" value="<?php echo $this->facetGroup->getId(); ?>" />

	<table>
	<tr><td><label for="facetGroup-name" class="required">Name</label></td>
		<td><input name="facetGroup[name]" id="facetGroup-name" value="<?php echo $this->facetGroup->getName(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/facets';">Cancel</button>
</fieldset>
</form>