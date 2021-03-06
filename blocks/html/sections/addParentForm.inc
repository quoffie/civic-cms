<?php
/**
 * @copyright Copyright (C) 2007-2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Section $this->section
 */
	function listSections($parent,$currentSection,$url)
	{
		foreach($parent->getChildren() as $section)
		{
			$name = View::escape($section->getName());
			$url->parameters['parent_id'] = $section->getId();
			echo "<li>";

				if ($currentSection->getId() != $section->getId() && !$currentSection->hasParent($section))
				{
					echo "
					<a href=\"$url\">$name</a>
					<a class=\"addSmall button\" href=\"$url\">Add</a>
					";
				}
				else { echo "<a href=\"#\">$name</a>"; }

			if ($section->hasChildren())
			{
				echo "<ul>";
				listSections($section,$currentSection,$url);
				echo "</ul>";
			}

			echo "</li>";
		}
	}
?>
<div class="interfaceBox">
	<h2>Add Parents</h2>

	<p>Choose parents for this section:</p>
	<ul><li>
		<?php
			# Start with the top section
			$section = new Section(1);
			$name = View::escape($section->getName());

			$url = new URL($_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']);
			$url->parameters['section_id'] = $this->section->getId();
			$url->parameters['parent_id'] = $section->getId();

			if ($this->section->getId()!=$section->getId() && !$this->section->hasParent($section))
			{
				echo "
				<a href=\"$url\">$name</a>
				<a class=\"addSmall button\" href=\"$url\">Add</a>
				";
			}
			else { echo "<a href=\"#\">$name</a>"; }

			echo "<ul class=\"expandable\">";
			listSections($section,$this->section,$url);
			echo "</ul>";
		?>
		</li>
	</ul>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/tree.js"></script>
</div>
