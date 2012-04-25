<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');

// start page output
echo "<div id=\"page\" class=\"art-Post\">\n";

// page title
if ($this->params->get('show_page_title', 1)) {
	echo "\t<div class=\"art-PostHeader\">\n";
	echo "\t\t" . $this->escape($this->params->get('page_title')) . "\n";
	echo "\t</div><!-- end div.art-PostHeader -->\n";
}

// page content
echo "\t<div class=\"art-PostContent\">\n";

// description image
if ($this->params->get('show_description_image') && $this->section->image) {
	echo "\t\t<div class=\"art-PostDescriptionImage\">\n";
	$imgUrl = $this->baseurl . '/' . $cparams->get('image_path') . '/' . $this->section->image;
	echo "\t\t\t<img src=\"{$imgUrl}\" alt=\"{$this->section->image}\" />\n";
	echo "\t\t</div><!-- end div.art-PostDescriptionImage -->\n";
}

// description text
if ($this->params->get('show_description') && $this->section->description) {
	echo "\t\t<div class=\"art-PostDescription\">\n";
	echo "\t\t\t{$this->section->description}\n";
	echo "\t\t</div><!-- end div.art-PostDescription -->\n";
}

// list categories
if ($this->params->get('show_categories', 1)) {
	// get the type of list
	$showCatDesc = $this->params->def('show_category_description', 1);
	$listType = $showCatDesc ? 'dl' : 'ul';
	echo "\t\t<{$listType} class=\"jw-CatSectionList\">\n";

	// start looking at list items
	$showEmpties = $this->params->get('show_empty_categories');
	$showNumArticles = $this->params->get('show_cat_num_articles');
	$listItemType = $showCatDesc ? 'dt' : 'li';

	// go through the categories
	foreach ($this->categories as $category) {
		if (!$showEmpties && !$category->numitems) {
			continue;
		}
		echo "\t\t\t<{$listItemType}>\n";
		echo "\t\t\t\t<a href=\"{$category->link}\">";
		echo $this->escape($category->title);
		echo "</a>";

		// check for number of articles
		if ($showNumArticles) {
			$itemText = $category->numitems == 1 ? ' item' : ' items';
			echo "&nbsp;({$category->numitems} {$itemText})";
		}
		echo "\n";
		echo "\t\t\t<{$listItemType}>\n";

		// category description
		if ($showCatDesc && $category->description) {
			echo "\t\t\t<dd>{$category->description}</dd>\n";
		}
	}
	echo "\t\t</ul><!-- end ul.jw-CatSectionList -->\n";
}

// close divs
echo "\t</div><!-- end div.art-PostContent -->\n";
echo "</div><!-- end div.art-Post -->\n";
