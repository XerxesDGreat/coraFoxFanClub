<?php
defined('_JEXEC') or die('Restricted access'); // no direct access
$canEdit = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));

$item = &$this->item;
$params = &$item->params;
?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>

<div class="art-Post">
	<?php
	if ($params->get('show_title')) {
		$title = $this->escape($item->title);
	?>
	<div class="art-PostHeader">
		<?php
		if ($params->get('link_titles') && $item->readmore_link != '') {
		?>
		<a href="<?php echo $item->readmore_link ?>" class="PostHeader"><?php echo $title ?></a>
		<?php
		} else {
			echo $title;
		}
		?>
	</div><!-- end div.art-PostHeader -->
	<?php
	}
	?>

	<div class="art-PostHeaderIcons art-metadata-icons">
		<?php
		if ($this->params->get('show_url') && $this->article->urls) {
    		echo('<a href="http://' . $this->item->urls . '" target="_blank">' . $this->item->urls . '</a> |');
		}
		if ($params->get('show_create_date')) {
			echo(JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')) . ' | ');
		}
		if (($params->get('show_author')) && ($this->item->author != "")) {
			echo(JText::sprintf('Written by', ($this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author)) . ' | ');
		}
		if ($params->get('show_pdf_icon')) {
			echo(JHTML::_('icon.pdf', $this->item, $this->item->params, $this->access) . ' | ');
		}
		if ($params->get('show_print_icon')) {
			echo(JHTML::_('icon.print_popup', $this->item, $this->item->params, $this->access) . ' | ');
		}
		if ($params->get('show_email_icon')) {
			echo(JHTML::_('icon.email', $this->item, $this->item->params, $this->access) . ' | ');
		}
		if ($canEdit) {
			echo(JHTML::_('icon.edit', $this->item, $this->item->params, $this->access) . ' | ');
		}
		?>
	</div><!-- end div.art-PostHeaderIcons -->

	<div class="art-PostContent">
		<?php
		if (!$params->get('show_intro')) {
			echo $this->item->event->afterDisplayTitle;
		}
		echo $this->item->event->beforeDisplayContent;
		if (($params->get('show_section') && $this->item->sectionid) || ($params->get('show_category') && $this->item->catid)) {
		?>
		<table class="contentpaneopen<?php echo $params->get('pageclass_sfx' ); ?>">
			<tr>
				<td>
					<?php
					if ($params->get('show_section') && $this->item->sectionid && isset($this->item->section)) {
						echo "<span>";
						if ($params->get('link_section')) {
							echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'">';
						}
						echo $this->item->section;
						if ($params->get('link_section')) {
							echo '</a>';
						}
						if ($params->get('show_category')) {
							echo ' - ';
						}
						echo "</span>";
					}
					if ($params->get('show_category') && $this->item->catid) {
						echo "<span>";
						if ($params->get('link_category')) {
							echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'">';
						}
						echo $this->item->category;
						if ($params->get('link_category')) {
							echo '</a>';
						}
						echo "</span>";
					}
					?>
				</td>
			</tr>
		</table>
		<?php
		}
		if (isset ($this->item->toc)) {
			echo $this->item->toc;
		}
		?>
		<div class="art-article">
			<?php echo($this->item->text); ?>
		</div><!-- end div.art-article -->
		<?php
		if (intval($this->item->modified) != 0 && $params->get('show_modify_date')) {
			echo "<p class=\"modifydate\">";
			echo JText::_('Last Updated') . ' (' . JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2')) . ')';
			echo "</p>";
		}
		if ($params->get('show_readmore') && $this->item->readmore) {
		?>
		<p>
			<span class="art-button-wrapper">
				<span class="l"> </span>
				<span class="r"> </span>
				<a class="readon art-button" href="<?php echo $this->item->readmore_link; ?>">
					<?php
   					if ($this->item->readmore_register) {
						echo str_replace(' ', '&nbsp;', JText::_('Register to read more...'));
					} elseif ($readmore = $params->get('readmore')) {
						echo str_replace(' ', '&nbsp;', $readmore);
					} else {
						echo str_replace(' ', '&nbsp;', JText::sprintf('Read more...'));
					}
					?>
				</a>
 			</span>
		</p>
		<?php
		}
		?>
		<span class="article_separator">&nbsp;</span>
		<?php
		echo $this->item->event->afterDisplayContent;
		?>
		<div class=\"cleared\"></div>
	</div><!-- end div.art-PostContent -->
	<div class="cleared"></div>
</div><!-- end div.art-Post -->

<?php if ($this->item->state == 0) { ?>
</div><!-- end div.system-unpublished -->
<?php } ?>
