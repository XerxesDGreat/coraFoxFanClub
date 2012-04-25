<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$moduleClass = $params->get('moduleclass_sfx');

// title
if ($params->get('item_title')) {
	echo "<div class=\"art-ContentPaneHeader{$moduleClass}\">\n";
	if ($params->get('link_titles') && $item->linkOn != '') {
		echo "\t<a href=\"{$item->linkOn}\">{$item->title}</a>\n";
	} else {
		echo "\t{$item->title}\n";
	}
	echo "</div><!-- end div.art-ContentPaneHeader -->\n";
}

// we want the content plugins to be processed; otherwise, things like galleries
// and vids won't show up
$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger('onPrepareContent', array(&$item, &$params));

// after display title
if (!$params->get('intro_only')) {
	echo $item->afterDisplayTitle;
}

// before display content
echo $item->beforeDisplayContent;

// content
echo "<div class=\"art-ContentPaneContent{$moduleClass}\">\n";
echo "\t{$item->text}\n";
if (isset($item->linkOn) && $item->readmore && $params->get('readmore')) {
	echo "\t<div class=\"art-ContentPaneReadMore\">\n";
	echo "\t\t<a href=\"{$item->linkOn}\">{$item->linkText}</a>\n";
	echo "\t</div><!-- end div.art-ContentPaneReadMore -->\n";
}
echo "</div><!-- end div.art-ContentPaneContent -->\n";
