<?php
/**
 * Renders links to facets
 *
 * If you specify a FacetGroup, this widget will list the facets in the chosen group.
 * If you do not specify a FacetGroup, this widget will list all of the current document's facets
 * If listed, the current document's facets will be split up by facet group.
 *
 * @copyright 2007-2008 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class FacetWidget extends Widget
{
	/**
	 * The group this widget is supposed to render.
	 */
	public $facetGroup;

	public function __construct()
	{
		$this->className = 'FacetWidget';
		$this->displayName = 'Facet Links';
		$this->includeFile = 'sidebar.inc';
		$this->description = 'Displays links to the facets in the chosen group';
	}

	public function render($template)
	{
		echo $this->renderIncludeFile($this);
	}

	/**
	 * The application can call this to find out whether this widget uses stored data
	 * @return boolean
	 */
	public function usesData()
	{
		return true;
	}
	/**
	 * Unserializes the string field from the database into a variable
	 * @param string $string
	 */
	public function setData($string)
	{
		$this->facetGroup = new FacetGroup($string);
	}
	/**
	 * Serializes an array into a string that can be inserted into the database
	 * This will usually be from a form posting.  The form should have used
	 * this widget's dataFields include so we know what array to expect
	 * @param array $post
	 * @return string Serialized data for the widget
	 */
	public function serializePost(array $post)
	{
		if (isset($_POST['facetGroup_id'])) {
			return $_POST['facetGroup_id'];
		}
	}
	/**
	 * This lets the application ask for the path to the file holding the form fields
	 * this widgets wants used
	 * @return string Path to include file
	 */
	public function getDataFieldsetInclude()
	{
		return dirname(__FILE__).'/dataFields.inc';
	}
}
