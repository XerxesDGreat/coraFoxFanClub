<?php
defined('_JEXEC') or die('Restricted access'); // no direct access
$pathToFunctions = realpath(dirname(__FILE__) . '/../../../');
require_once($pathToFunctions . DIRECTORY_SEPARATOR . 'functions.php');
require_once($pathToFunctions . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'jw.php');

// shortcuts
$item = $this->item;
$params = $item->params;

$indent = 0;

// check for publish status
if ($this->item->state == 0) {
	JW::out('<div class="system-unpublished">', $indent);
}

JW::out('<div class="art-Post">', $indent ++);

// title/header section
if ($params->get('show_title')) {
	JW::out('<h2 class="art-PostHeader">', $indent ++);

	if ($params->get('link_titles') && $item->readmore_link != '') {
		JW::out('<a href="' . $item->readmore_link . '" class="PostHeader">' . $this->escape($item->title) . '</a>', $indent --);
	} else {
		JW::out($this->escape($item->title, $indent --));
	}

	JW::out('</h2>', $indent);
}

// metadata section
$chunkKey = JW::startChunks('<div class="art-PostMetaData>');
if ($params->get('show_url') && $this->article->urls) {
	JW::addChunk($chunkKey, '<a href="http://' . $item->urls . '" target="_blank">' . $item->urls . '</a>');
}
if ($params->get('show_create_date')) {
	JW::addChunk($chunkKey, JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC2')));
}
if (($params->get('show_author')) && ($item->author != "")) {
	JW::addChunk($chunkKey, JText::sprintf('Written by', ($item->created_by_alias ? $item->created_by_alias : $item->author)));
}
if ($params->get('show_pdf_icon')) {
	JW::addChunk($chunkKey, JW::getIcon('pdf', $this));
}
if ($params->get('show_print_icon')) {
	JW::addChunk($chunkKey, JW::getIcon('print_popup', $this));
}
if ($params->get('show_email_icon')) {
	JW::addChunk($chunkKey, JW::getIcon('email', $this));
}
if (JW::canEdit($this->user)) {
	JW::addChunk($chunkKey, JW::getIcon('edit', $this));
}
JW::endChunks($chunkKey, "</div><!-- end div.art-PostMetaData -->");
JW::out(JW::getAndClearChunks($chunkKey, ' | '), $indent);

// begin content
JW::out('<div class="art-PostContent">', $indent ++);

// introduction and pre-content content (e.g. ads)
if (!$params->get('show_intro')) {
	JW::out($item->event->afterDisplayTitle, $indent);
}
JW::out($item->event->beforeDisplayContent, $indent);

// section/category links
if (JW::shouldShowSection($item) || JW::shouldShowCategory($item)) {
	$class = 'contentpaneopen' . $params->get('pageclass_sfx');
	$chunkKey = JW::startChunks("<div class=\"{$class}\">");
	JW::addChunk($chunkKey, JW::getSectionHTML($item));
	JW::addChunk($chunkKey, JW::getCategoryHTML($item));
	JW::endChunks($chunkKey, "</div><!-- end div.{$class} -->");
	JW::out(JW::getAndClearChunks($chunkKey), $indent);
}

// table of contents
if (isset ($item->toc)) {
	JW::out($item->toc, $indent);
}

// article
JW::out('<div class="art-article">', $indent ++);
JW::out($item->text, $indent --);
JW::out('</div><!-- end div.art-article -->', $indent);

// modification date
if (intval($item->modified) != 0 && $params->get('show_modify_date')) {
	JW::out('<p class=\"modifydate\">', $indent++);
	JW::out(JText::_('Last Updated') . ' (' . JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2')) . ')', $indent --);
	JW::out("</p>", $indent);
}

// read more button.
// @todo change this into a button
if ($params->get('show_readmore') && $item->readmore) {
	JW::out('<p>', $indent ++);
	JW::out('<span class="art-button-wrapper">', $indent ++);
	JW::out('<span class="l"> </span>', $indent);
	JW::out('<span class="r"> </span>', $indent);
	JW::out('<a class="readon art-button" href="' . $item->readmore_link . '">', $indent ++);
	if ($item->readmore_register) {
		JW::out(str_replace(' ', '&nbsp;', JText::_('Register to read more...')), $indent --);
	} else if ($params->get('readmore')){
		JW::out(str_replace(' ', '&nbsp;', $params->get('readmore')), $indent --);
	} else {
		JW::out(str_replace(' ', '&nbsp;', JText::sprintf('Read more...')), $indent --);
	}
	JW::out('</a>', $indent --);
	JW::out('</span>', $indent --);
	JW::out('</p>', $indent --);
}

JW::out('<span class="article_separator">&nbsp;</span>', $indent);
JW::out($item->event->afterDisplayContent, $indent --);
JW::out('</div><!-- end div.art-PostContent -->', $indent);
JW::out('<div class="cleared"> </div>', $indent --);
JW::out('</div><!-- end div.art-Post -->', $indent);

if ($this->item->state == 0) {
	JW::out('</div><!-- end div.system-unpublished -->', $indent);
}
