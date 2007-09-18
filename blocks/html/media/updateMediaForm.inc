<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Media $this->media
 */
?>
<h1>Update Media</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
<fieldset><legend>Media Info</legend>
	<input name="media_id" type="hidden" value="<?php echo $this->media->getId(); ?>" />
	<table>

	<tr><td><label for="media-title" class="required">title</label></td>
		<td><input name="media[title]" id="media-title" value="<?php echo $this->media->getTitle(); ?>" /></td></tr>

	<tr><td><label for="media-description" class="required">description</label></td>
		<td><textarea name="media[description]" id="media-description" rows="3" cols="60"><?php echo $this->media->getDescription(); ?></textarea></td></tr>

	</table>
</fieldset>

<fieldset><legend>Upload a new version of this file</legend>
	<p>This will replace the file on the server with whatever you upload.</p>
	<input name="upload" type="file" />
	<input name="max_file_size" type="hidden" value="<?php echo 1000000 * (int)ini_get('upload_max_filesize'); ?>" />
	<button type="submit" class="upload">Upload</button>
</fieldset>

<fieldset><legend>Submit</legend>
	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/media/info.php?media_id=<?php echo $this->media->getId(); ?>';">Cancel</button>
</fieldset>

</form>