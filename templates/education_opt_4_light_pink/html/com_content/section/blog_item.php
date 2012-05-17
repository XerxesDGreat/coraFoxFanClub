<?php
defined('_JEXEC') or die('Restricted access'); // no direct access

$indent = 0;
$item = $this->item;

if ($item->state == 0) {
	JW::out('<div class="system-unpublished">');
	$indent ++;
}

JW::out('<div class="art-SubPost">', $indent);
$indent ++;

// check for the title
if ($item->params->get('show_title')) {
	JW::out('<div class="art-SubPostHeader">', $indent);
	$indent ++;
	$title = $this->escape($item->title);
	if ($item->params->get('link_titles') && $item->readmore_link != '') {
		$link = $item->readmore_link;
		JW::out("<a href=\"{$link}\">{$title}</a>", $indent);
	} else {
		JW::out($title, $indent);
	}
	$indent --;
	JW::out('</div><!-- end div.art-SubPostHeader -->', $indent);
}

$chunkKey = JW::startChunks('<div class="art-SubPostMetaData">');
if ($this->params->get('show_url') && $this->article->urls) {
	JW::addChunk($chunkKey, '<a href="http://' . $item->urls . '" target="_blank">' . $item->urls . '</a>');
}
if ($item->params->get('show_create_date')) {
	JW::addChunk($chunkKey, JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC2')));
}
if (($item->params->get('show_author')) && ($item->author != "")) {
	JW::addChunk($chunkKey, JText::sprintf('Written by', ($item->created_by_alias ? $item->created_by_alias : $item->author)));
}
if ($item->params->get('show_pdf_icon')) {
	JW::addChunk($chunkKey, JW::getIcon('pdf', $this));
}
if ($item->params->get('show_print_icon')) {
	JW::addChunk($chunkKey, JW::getIcon('print_popup', $this));
}
if ($item->params->get('show_email_icon')) {
	JW::addChunk($chunkKey, JW::getIcon('email', $this));
}
if (JW::canEdit($this->user)) {
	JW::addChunk($chunkKey, JW::getIcon('edit', $this));
}
JW::endChunks($chunkKey, "</div><!-- end div.art-SubPostMetaData -->\r\n");
JW::out(JW::getChunks($chunkKey, ' | '));
JW::clearChunks($chunkKey);

JW::out('<div class="art-SubPostContent">', $indent);
$indent ++;
if (!$item->params->get('show_intro')) {
	JW::out($item->event->afterDisplayTitle, $indent);
}
JW::out($item->event->beforeDisplayContent, $indent);
if (JW::shouldShowSection($item) || JW::shouldShowCategory($item)) {
	$class = 'contentpaneopen' . $item->params->get('pageclass_sfx');
	$chunkKey = JW::startChunks("<div class=\"{$class}\">");
	JW::addChunk($chunkKey, JW::getSectionHTML($item));
	JW::addChunk($chunkKey, JW::getCategoryHTML($item));
	JW::endChunks($chunkKey, "</div><!-- end div.{$class} -->");
	JW::out(JW::getAndClearChunks($chunkKey), $indent);
}
if (isset ($item->toc))
 echo $item->toc;
echo "<div class=\"art-article\">", $item->text, "</div>";
if (intval($item->modified) != 0 && $item->params->get('show_modify_date')) {
 echo "<p class=\"modifydate\">";
 echo JText::_('Last Updated') . ' (' . JHTML::_('date', $item->modified, JText::_('DATE_FORMAT_LC2')) . ')';
 echo "</p>";
}
if ($item->params->get('show_readmore') && $item->readmore) {
?>
<p>
 <span class="art-button-wrapper">
  <span class="l"> </span>
  <span class="r"> </span>
  <a class="readon art-button" href="<?php echo $item->readmore_link; ?>">
  <?php
   if ($item->readmore_register) {
    echo str_replace(' ', '&nbsp;', JText::_('Register to read more...'));
   } elseif ($readmore = $item->params->get('readmore')){
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
echo "<span class=\"article_separator\">&nbsp;</span>";
echo $item->event->afterDisplayContent;
echo "\r\n<div class=\"cleared\"></div>\r\n";
?>

	</div><!-- end div.art-SubPostContent -->

	<div class="cleared"></div>
</div><!-- end div.art-SubPost -->


<?php if ($item->state == 0) : ?>
</div>
<?php endif; ?>
