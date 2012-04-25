<?php // @version $Id: default.php 11917 2009-05-29 19:37:05Z ian $
defined('_JEXEC') or die('Restricted access');

// shortcuts
$article = &$this->article;
$params = &$this->params;

$pageClass = $this->escape($params->get('pageclass_sfx'));
require(dirname(__FILE__) . '/../../../functions.php');

// start page output
echo "<div id=\"page\" class=\"art-Post\">";

// edit pane
if (artxShouldAllowEdit($this)) {
	echo "\t<div class=\"contentpaneopen_edit{$pageClass}\">\n";
	echo "\t\t" . JHTML::_('icon.edit', $article, $params, $this->access) . "\n";
	echo "\t</div><!-- end div.contentpaneopen_edit{$pageClass} -->\n";
}

// page title
if ($params->get('show_title')) {
	echo "\t<div class=\"art-PostHeader\">\n";
	if ($params->get('link_titles') && $article->readmore_link != '') {
		echo "\t\t<a href=\"{$article->readmore_link}\" class=\"contentpagetitle{$pageClass}\">";
		echo $this->escape($article->title);
		echo "</a>\n";
	} else {
		echo "\t\t" . $this->escape($article->title) . "\n";
	}
	echo "\t</div><!-- end div.art-PostHeader -->\n";
}

// publish date
$showButtons = $this->print || $params->get('show_pdf_icon')
	|| $params->get('show_print_icon') || $params->get('show_email_icon');
if (
	$params->get('show_create_date')
	|| $showButtons
	|| true
) {
	echo "\t<div class=\"art-PostMetaData\">\n";
	if ($params->get('show_create_date')) {
		echo "\t\tPosted on " . JHTML::_('date', $article->created, JText::_('DATE_FORMAT_LC2'));
		if ($showButtons) {
			echo "&nbsp;|&nbsp;";
		}
		echo "\n";
	}
	if ($this->print) {
		echo "\t\t" . JHTML::_('icon.print_screen', $article, $params, $this->access) . "\n";
	} else {
		$template = $mainframe->getTemplate();
		echo "\t\t<img src=\"{$this->baseurl}/templates/{$template}/images/trans.gif\" />\n";
		// again, I think this could be streamlined
		// @todo improve this; dry
		if ($params->get('show_pdf_icon')) {
			echo "\t\t" . JHTML::_('icon.pdf', $article, $params, $this->access) . "\n";
		}
		if ($params->get('show_print_icon')) {
			echo "\t\t" . JHTML::_('icon.print_popup', $article, $params, $this->access) . "\n";
		}
		if ($params->get('show_email_icon')) {
			echo "\t\t" . JHTML::_('icon.email', $article, $params, $this->access) . "\n";
		}
	}
	echo "\t</div><!-- end div.art-PostMetaData -->\n";
}

// after display title section
if (!$params->get('show_intro')) {
	echo $article->event->afterDisplayTitle;
}

// before display content
echo $article->event->beforeDisplayContent;

// urls (though I don't really know what this is....)
if ($params->get('show_url') && $article->urls) {
	echo "\t<span class=\"small\">";
	$urls = $this->escape($article->urls);
	echo "<a href=\"{$urls}\" target=\"_blank\">";
	echo $urls;
	echo "</a>";
	echo "</span>\n";
}

// table of contents
if (isset ($article->toc)) {
	echo $article->toc;
}

// actual content (finally!)
echo "\t<div class=\"art-PostContent\">\n";
echo JFilterOutput::ampReplace($article->text);
echo "\t</div><!-- end div.art-PostContent -->\n";

// more metadata
if (
	$params->get('show_author') && (!empty($article->author))
) {
	echo "\t<div class=\"art-PostMetaDataEnd\">\n";
	$metaDataItems = array();
	$showCat = (true || $params->get('show_category')) && $article->catid;

	// author
	if (($params->get('show_author')) && ($article->author != "")) {
		$author = $article->created_by_alias ? $article->created_by_alias : $article->author;
		$text = JText::sprintf('Written by', $this->escape($author));
		if ($showCat) {
			$text .= "&nbsp;|&nbsp;\n";
		}
		$metaDataItems[] = $text;
	}

	// category
	if ($showCat) {
		$catString = $this->escape($article->category);
		if ($params->get('link_category') || true) {
			$catLink = JRoute::_(ContentHelperRoute::getCategoryRoute($article->catslug, $article->sectionid));
			$text = '<a href="' . $catLink . '">' . $catString . '</a>';
		} else {
			$text = $catString;
		}
		$metaDataItems[] = 'Posted in ' . $text;
	}

	foreach($metaDataItems as $metaDataItem) {
		echo "\t\t<span>{$metaDataItem}</span>\n";
	}
	echo "\t</div><!-- end div.art-PostMetaDataEnd -->\n";
}

// get comments
echo "\t<div class=\"art-PostComments\">\n";
echo "\t\t<div class=\"art-SubPostHeader\">\n";
echo "\t\t\tLeave a comment!\n";
echo "\t\t</div><!-- end div.art-SubPostHeader -->\n";
echo "\t\t<fb:comments href=\"http://www.corafoxfanclub.com\" num_posts=\"5\" width=\"513\">\n";
echo "\t\t</fb:comments>\n";
echo "\t</div><!-- end div.art-PostComments -->\n";

// after content display
echo $article->event->afterDisplayContent;

// 

// closing div
echo "</div><!-- end div#page/.art-Post -->\n";
