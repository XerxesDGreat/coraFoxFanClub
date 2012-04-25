<?php
defined('_JEXEC') or die('Restricted access'); // no direct access
$pathToFunctions = dirname(__FILE__) . str_replace('/', DIRECTORY_SEPARATOR, '/../../../');
require_once("{$pathToFunctions}functions.php");
require_once("{$pathToFunctions}jw_functions.php");

// set up some shortcuts
$cparams = JComponentHelper::getParams('com_media');
$params = $this->params;
$section = $this->section;
$pagination = $this->pagination;

// begin page output
JW::out('<div class="art-Post">');

// page title header
if ($params->def('show_page_title', 1)) {
	JW::out('<div class="art-PostHeader">', 1);
	JW::out($this->escape($params->get('page_title')), 2);
	JW::out('</div><!-- end art-PostHeader -->', 1);
}

// description and/or image
if (JW::shouldShowDescriptionImage($this)
	|| JW::shouldShowDescription($this)
) {
	JW::out('<div class="art-PostContent">', 1);
	if (JW::shouldShowDescriptionImage($this)) {
		$imagePath = $cparams->get('image_path');
		$imageSource = "{$this->baseurl}/{$imagePath}/{$section->image}";
		$imageStyle = "vertical-align: {$section->image_position}";

		JW::out("<img src\"={$imageSource}\" style=\"{$imageStyle};\" />", 2);
	}
	if (JW::shouldShowDescription($this)) {
		JW::out($section->description, 2);
	}
	JW::out('</div><!-- end art-PostContent -->', 1);
}

// any leading articles
$articleStart = $pagination->limitstart;
if ($params->def('num_leading_articles', 1)) {
	JW::out('<div class="blog' . $params->get('pageclass_sfx') . '">', 1);
	$maxIterations = $articleStart + $params->get('num_leading_articles');
	$pageLimit = $articleStart + $params->get('num_leading_articles');
	for ($i = $articleStart; $i < $pageLimit; $i ++, $articleStart ++) {
		if ($i >= $this->total) {
			break;
		}
		JW::out('<div>', 1);
		$this->item =& $this->getItem($i, $this->params);
		JW::out($this->loadTemplate('item'), 2);
		JW::out('</div>', 1);

	}
	JW::out('</div><!-- end div.blog -->', 1);
}

// introductive articles
$startIntroArticles = $articleStart + $params->get('num_leading_articles');
$numIntroArticles = $startIntroArticles + $params->get('num_intro_articles', 4);
if (($numIntroArticles != $startIntroArticles) && ($articleStart < $this->total)) {
	JW::out('<div>', 1);
	$divider = '';
	// check the columns to determine the layout
	$numColumns = $params->get('num_columns', 2);
	if ($params->get('multi_column_order')) { // order across, like front page
		for ($z = 0; $z < $numColumns; $z ++) {
			if ($z > 0) {
				$divider = " column_separator";
			}
			// put the opening container together
			$rows = floor($numIntroArticles / $numColumns);
			$cols = ($numIntroArticles % $numColumns);
			$class = "article_column{$divider}";
			$width = floor(100 / $numColumns) . "%";
			JW::out("<div class=\"{$class}\" width=\"{$width}\">", 2);

			// add the articles
			$loopLength = (($z < $cols) ? 1 : 0) + $rows;
			for ($y = 0; $y < $loopLength; $y ++) {
				$target = $i + ($y * $numColumns) + $z;
				if ($target < $this->total && $target < $numIntroArticles) {
					$this->item =& $this->getItem($target, $this->params);
					JW::out($this->loadTemplate('item'), 3);
				}
			}
			JW::out("</div><!-- end div.{$class} -->", 2);
		}

		$articleStart += $numIntroArticles; 
	} else { // otherwise, order down, same as before (default behaviour)
		for ($z = 0; $z < $numColumns; $z ++) {
			if ($z > 0) {
				$divider = " column_separator";
			}
			
			$class = "article_column{$divider}";
			$width = floor(100 / $numColumns) . "%";
			JW::out("<div class=\"{$class}\" width=\"{$width}\">", 2);

			$loopLength = floor($numIntroArticles / $numColumns);
			for ($y = 0; $y < $loopLength; $y ++) {
				if ($articleStart < $this->total && $articleStart < $numIntroArticles) {
					$this->item =& $this->getItem($i, $this->params);
					JW::out($this->loadTemplate('item'), 3);
					$articleStart ++;
				}
			}
			JW::out("</div><!-- end div.{$class} -->", 2);
		}
	}
	JW::out("</div><!-- end generic div -->", 1);
}
JW::out("</div><!-- end div.blog -->");

// add links
if ($params->get('num_links', 4) && ($articleStart < $this->total)) {
	$this->links = array_splice($this->items, $articleStart - $pagination->limitstart);
	if (count($this->links) > 0) {
		artxPost(null, $this->loadTemplate('links'));
	}
}

// show page numbers
$showPagination = $params->get('show_pagination', 2);
if ($showPagination == 1 || $showPagination == 2 && $pagination->get('pages.total') > 1) {
	$pages = "";
	$pages .= JW::out('<div id="navigation">', 0, false, true);
	$pages .= JW::out('<p>' . $pagination->getPagesLinks() . '</p>', 1, false, true);
	if ($params->get('show_pagination_results', 1)) {
		$pages .= JW::out('<p>' . $pagination->getPagesCounter() . '</p>', 1, false, true);
	}
	$pages .= JW::out('</div><!-- end div#navigation -->', 0, false, true);
	JW::out(JW::makePost(null, $pages));
}
