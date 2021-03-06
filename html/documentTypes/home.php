<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();
$template->blocks[] = new Block('documentTypes/breadcrumbs.inc');
$template->blocks[] = new Block('documentTypes/documentTypeList.inc');
echo $template->render();
