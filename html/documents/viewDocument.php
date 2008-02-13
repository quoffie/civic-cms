<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET document_id
 * @param POST document_id
 * @param array $navigation
 *
 * Documents in the system that have forms should post to themselves.
 * Forms must include document_id as a hidden field.
 * Documents that include forms are expected to have the PHP code
 * inside themselves to process their own POST
 */
	$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'html';
	switch($format)
	{
		case 'print': $template = new Template('print','html'); break;
		default: $template = new Template();
	}

	try
	{
		if (isset($_GET['document_id'])) { $document = new Document($_GET['document_id']); }
		if (isset($_POST['document_id'])) { $document = new Document($_POST['document_id']); }
	}
	catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }

	if (isset($document))
	{
		$document->logAccess();

		$template->document = $document;
		$template->title = $document->getTitle();

		#------------------------------------------------------------
		# Set up the BreadCrumbs
		#------------------------------------------------------------
		$p = isset($_SESSION['previousSectionId']) ? $_SESSION['previousSectionId'] : null;
		if (isset($_GET['section_id']))
		{
			$s = $_GET['section_id'];
			$_SESSION['previousSectionId'] = $s;
		}
		else { $s = null; }
		$currentAncestors = $document->getBreadcrumbs($s,$p);
		$template->currentAncestors = $currentAncestors;

		$breadcrumbs = new Block('documents/breadcrumbs.inc');
		$breadcrumbs->document = $document;
		$breadcrumbs->currentAncestors = $currentAncestors;
		$template->blocks[] = $breadcrumbs;

		# Current Ancestors should be set by now, so we should know where we are
		$currentSection = $s ? new Section($s) : end($currentAncestors);

		#------------------------------------------------------------
		# Set up the content of the document
		#------------------------------------------------------------
		$viewDocument = new Block('documents/viewDocument.inc');
		$viewDocument->document = $document;
		$viewDocument->section = $currentSection;

		if (!$document->isActive())
		{
			$template->blocks[] = new Block('documents/unavailable.inc',array('document'=>$document));

			if (isset($_SESSION['USER']) && $document->permitsEditingBy($_SESSION['USER']))
			{
				$template->blocks[] = $viewDocument;
			}
			else
			{
				try
				{
					$search = new Search();
					$results = $search->find($document->getTitle());
				}
				catch (Exception $e) { exception_handler($e); }


				$currentType = isset($_GET['type']) ? Inflector::pluralize($_GET['type']) : 'Documents';
				$type = strtolower($currentType);


				if (isset($results[$type]) && count($results[$type]))
				{
					# If we've got a lot of results, split them up into seperate pages
					if ($results[$type] > 10)
					{
						$resultArray = new ArrayObject($results[$type]);
						$pages = new Paginator($resultArray,10);

						# Make sure we're asking for a page that actually exists
						$page = (isset($_GET['page']) && $_GET['page']) ? (int)$_GET['page'] : 0;
						if (!$pages->offsetExists($page)) { $page = 0; }

						$resultsList = new LimitIterator($resultArray->getIterator(),$pages[$page],$pages->getPageSize());
					}
					else { $resultsList = $this->results[$type]; }
				}
				else { $resultsList = array(); }
				if (isset($results))
				{
					$resultsTab = new Block('search/resultTabs.inc');
					$resultsTab->currentType = $currentType;
					$resultsTab->type = $type;
					$resultsTab->results = $results;

					$resultBlock = new Block('search/results.inc');
					$resultBlock->results = $resultsList;
					$resultBlock->currentType = $currentType;
					$resultBlock->type = $type;

					$template->blocks[] = $resultsTab;
					$template->blocks[] = $resultBlock;

					if (isset($pages))
					{
						$pageNavigation = new Block('pageNavigation.inc');
						$pageNavigation->page = $page;
						$pageNavigation->pages = $pages;
						$pageNavigation->url = new URL($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

						$template->blocks[] = $pageNavigation;
					}
				}
			}
		}
		else
		{
			$template->blocks[] = $viewDocument;
		}


		# If we're viewing the homepage of the current section
		if ($currentSection->getDocument_id() === $document->getId())
		{
			# Check for Featured Documents in this Section
			$types = new DocumentTypeList();
			$types->find();
			foreach($types as $type)
			{
				$documentList = new DocumentList(array('documentType_id'=>$type->getId(),'section_id'=>$currentSection->getId(),'featured'=>1,'active'=>date('Y-m-d')));
				if (count($documentList))
				{
					$featuredDocuments = new Block('sections/featuredDocuments.inc');
					$featuredDocuments->documentType = $type;
					$featuredDocuments->documentList = $documentList;
					$featuredDocuments->section = $currentSection;

					$template->blocks[] = $featuredDocuments;
				}
			}

			$template->blocks[] = new Block('sections/documents.inc',array('section'=>$currentSection,'document'=>$document));
		}
	}

	$template->render();
