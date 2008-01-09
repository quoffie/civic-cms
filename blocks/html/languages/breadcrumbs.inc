<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Language $this->language
 */
?>
<div class="breadcrumbs">
<ul><li><a href="<?php echo BASE_URL; ?>">Home</a></li>
	<li><a href="<?php echo BASE_URL; ?>/languages">Languages</a></li>
	<?php
		if (isset($this->language))
		{
			echo "<li>{$this->language->getEnglish()}</li>";
		}
	?>
</ul>
</div>