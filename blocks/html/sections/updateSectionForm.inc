<?php
/*
	$this->section
*/
	function printSectionOptions($section,$depth,$sectionToEdit)
	{
		$depthIndicator = str_repeat("-",$depth);
		$checked = $sectionToEdit->hasParent($section) ? "checked=\"checked\"" : "";
		echo "<li><label><input name=\"section[parents][{$section->getId()}]\" type=\"checkbox\" value=\"{$section->getId()}\" $checked />$section</label></li>";
		if ($section->hasChildren())
		{
			echo "<ul>";
			foreach($section->getChildren() as $child) { printSectionOptions($child,$depth+1,$sectionToEdit); }
			echo "</ul>";
		}
	}
?>
<h1>Edit <?php echo $this->section; ?></h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Section Info</legend>
	<input name="id" type="hidden" value="<?php echo $this->section->getId(); ?>" />
	<table>
	<tr><td><label for="section-name">Name</label></td>
		<td><input name="section[name]" id="section-name" value="<?php echo $this->section->getName(); ?>" /></td></tr>
	</table>
</fieldset>

<fieldset><legend>Departments</legend>
	<table>
	<?php
		$departments = new DepartmentList();
		$departments->find();
		foreach($departments as $department)
		{
			$checked = $this->section->hasDepartment($department) ? "checked=\"checked\"" : "";
			echo "
			<tr><td><input type=\"checkbox\" name=\"section[departments][{$department->getId()}]\" id=\"section-departments-{$department->getId()}\" value=\"{$department->getId()}\" $checked /></td>
				<td><label for=\"section-departments-{$department->getId()}\">{$department->getName()}</label></td></tr>
			";
		}
	?>
	</table>
</fieldset>

<fieldset><legend>Parents</legend>
	<ul style="list-style-type:none">
		<?php
			$sections = new SectionList(array("parent_id"=>"null"));
			foreach($sections as $section)
			{
				printSectionOptions($section,0,$this->section);
			}
		?>
	</ul>
</fieldset>

<fieldset><legend>Widgets</legend>
	<table>
	<tr><th></th>
		<th>Widget</th>
		<th>Description</th>
	</tr>
	<?php
		foreach(Widget::findInstalled() as $widget)
		{
			if ($this->section->hasWidget($widget)) { $checked = "checked=\"checked\""; } else { $checked = ""; }
			echo "
			<tr><td><input type=\"checkbox\" name=\"section[widgets][$widget]\" id=\"section-widgets-$widget\" value=\"$widget\" $checked /></td>
				<td><label for=\"section-widgets-$widget\">$widget</label></td>
				<td>{$widget->getDescription()}</td>
			</tr>
			";
		}
	?>
	</table>
</fieldset>

<fieldset><legend>Submit</legend>
	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/sections';">Cancel</button>
</fieldset>
</form>