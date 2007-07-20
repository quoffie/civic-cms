<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	class RelatedSectionsWidget extends Widget
	{
		protected $sectionList;

		public function __construct()
		{
			$this->className = 'RelatedSectionsWidget';
			$this->displayName = 'Related Sections';
			$this->includeFile = 'sidebar.inc';
			$this->description = 'Shows other sections the current section is a child of';
		}

		public function render($template)
		{
			global $section;
			if ($section)
			{
				if (isset($template->document))
				{
					$this->sectionList = $section->getParents();
					if (count($this->sectionList) > 1)
					{
						$this->renderIncludeFile($this);
					}
				}
			}
		}
	}
?>