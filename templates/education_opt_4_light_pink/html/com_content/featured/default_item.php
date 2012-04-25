<?php
/**
 * @version		$Id: default_item.php 21092 2011-04-06 17:12:16Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$canEdit = $this->item->params->get('access-edit');
?>

<?php
if ($this->item->state == 0) {
?>
<div class="system-unpublished">
<?php
}
?>
<?php
if ($params->get('show_title')) {
?>
	<h2>
<?php
	echo $this->escape($this->item->title);
?>
	</h2>
<?php
}
?>

<?php
echo $this->item->introtext;
?>

<?php
if ($params->get('show_readmore') && $this->item->readmore) {
	if ($params->get('access-view')) {
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	} else {
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	}
?>
	<p class="readmore">
		<a href="<?php echo $link; ?>">
<?php
	if (!$params->get('access-view')) {
		echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
	} else if ($readmore = $this->item->alternative_readmore) {
		echo $readmore;
		if ($params->get('show_readmore_title', 0) != 0) {
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		}
	} else if ($params->get('show_readmore_title', 0) == 0) {
		echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');	
	} else {
		echo JText::_('COM_CONTENT_READ_MORE');
		echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
	}
?>
		</a>
	</p>
<?php
}
?>

<?php
if ($this->item->state == 0) {
?>
</div>
<?php
}
?>

<div class="item-separator"></div>
<?php
echo $this->item->event->afterDisplayContent;
?>
