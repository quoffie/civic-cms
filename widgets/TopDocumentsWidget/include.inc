<?php
/**
 * @copyright Copyright (C) 2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param DocumentType $this->documentType
 */
	$name = View::escape(Inflector::pluralize($this->documentType->getType()));
?>
<div class="widget">
	<h1>Top <?php echo $name; ?></h1>
	<ol>
	<?php
		$hits = DocumentAccessLog::getTopDocuments(5,$this->documentType->getId());
		foreach($hits as $hit)
		{
			$title = View::escape($hit['document']->getTitle());
			echo "<li><a href=\"{$hit['document']->getURL()}\">$title</a></li>";
		}
	?>
	</ol>
</div>