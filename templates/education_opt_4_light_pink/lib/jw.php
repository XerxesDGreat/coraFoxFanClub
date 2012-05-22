<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'chunk_helper.php');
/**
 * A set of helper functions for the various template pages.
 * This class was originally created to centralize functionality for a
 * template found on 1800templates.com which was based on an earlier
 * version of php and Joomla (php4 and 5 friendly, Joomla 1.5).  This
 * class is for PHP 5.3+ and Joomla 1.5+.
 *
 * @author Josh Wickham
 * @created Sept 08, 2011
 */

final class JW {
	/**
	 * @var ARRAY $validIconTypes a list of valid icon types for building
	 * icon HTML strings
	 */
	private static $validIconTypes = array (
		'pdf', 'print_popup', 'email', 'edit'
	);

	private static $chunkHeaders = array();
	private static $chunks = array();
	private static $chunkFooters = array();

	/**
	 * whether or not to show the description image based on the app
	 * settings and on whether or not an image exists
	 *
	 * @param ContentView $page the page object
	 * @return BOOL
	 */
	public static function shouldShowDescriptionImage (ContentView $page) {
		if ($page->params->get('show_description_image')
			&& $page->section->image) {
			return true;
		}

		return false;
	}

	/**
	 * whether or not to show the textual description based on the app
	 * settings and on whether or not a description has been defined
	 *
	 * @param ContentView $page the page object
	 * @return BOOL
	 */
	public static function shouldShowDescription (ContentView $page) {
		if ($page->params->get('show_description') && $page->section->description) {
			return true;
		}

		return false;
	}

	/**
	 * whether or not to show the section based on section settings
	 * @param type $item
	 * @return boolean
	 */
	public static function shouldShowSection ($item) {
		if ($item->params->get('show_section') && $item->sectionid
			&& isset($item->section)
		) {
			return true;
		}
		return false;
	}

	public static function getSectionHTML ($item) {
		$output = '';
		if (!self::shouldShowSection($item)) {
			return $output;
		}

		$output = $item->section;
		$hasLink = $item->params->get('link_section');
		if ($hasLink) {
			$link = JRoute::_(ContentHelperRoute::getSectionRoute($item->sectionid));
			$output = "<a href=\"{$link}\">{$item->section}</a>";
		}
		return self::createDiv($output, 'jw-grouping');
	}

	public static function shouldShowCategory ($item) {
		if ($item->params->get('show_category') && $item->catid) {
			return true;
		}

		return false;
	}

	public static function getCategoryHTML ($item) {
		$output = '';
		if (!self::shouldShowCategory($item)) {
			return $output;
		}

		$output = $item->category;
		$hasLink = $item->params->get('link_category');
		if ($hasLink) {
			$link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug, $item->sectionid));
			$output = "<a href=\"{$link}\">{$output}</a>";
		}
		return self::createDiv($output, 'jw-grouping');
	}

	/**
	 * Builds a div element with the content and class defined
	 *
	 * @param STRING $content what should go in the div
	 * @param STRING $class the class of the div ['']
	 * @return STRING
	 */
	private static function createDiv ($content, $class = '') {
		if (empty($content)) {
			$content = '&nbsp;';
		}
		$output = '<div';
		if (!empty($class)) {
			$output .= ' class="' . $class . '"';
		}
		$output .= ">\n\t";
		$output .= $content;
		$output .= "\n</div><!-- end ";
		if (empty($class)) {
			$output .= "generic div";
		} else {
			$output .= "div.{$class}";
		}
		$output .= " -->\n";
		return $output;
	}

	/**
	 * Output the given string, indenting it the number of tabstops indicated.
	 * Also, add on a line break at the end of the line unless otherwise indicated
	 *
	 * @param STRING $str what to print
	 * @param INT $indentLevel how many tabstops to add [0]
	 * @param BOOL $noLineBreak whether to omit the linebreak [false]
	 * @param BOOL $return whether to return the value rather than echoinging it [false]
	 * @return VOID
	 */
	public static function out ($str, $indentLevel = 0, $noLineBreak = false, $return = false) {
		$output = '';
		if ($indentLevel > 0) {
			$output .= str_repeat("\t", $indentLevel);
		}
		$output .= $str;
		if (!$noLineBreak) {
			$output .= "\n";
		}

		if ($return) {
			return $output;
		}

		echo $output;
		return;
	}

	/**
	 * shortcut method for getting the author string to go in article metadata
	 * @param $item the item we're rendering
	 * @param String $prefix introduction text to the author's name
	 * @return String
	 */
	public static function getAuthorString ($item, $prefix = 'Written by') {
		$name = $item->created_by_alias ? $item->created_by_alias : $item->author;
		return JText::sprintf($prefix, $name);
	}

	/**
	 * Builds a post div with the possibility of a header and a body portion
	 * and returns the HTML string for the div.  Will indent based on the
	 * requested indent level and plug in the class passed through
	 *
	 * @param STRING $title the title of the post [null]
	 * @param STRING $body the body of the post [null]
	 * @param INT $indentLevel how many tabs should precede this block
	 * @param STRING $class the requested css class
	 * @return STRING
	 */
	public static function makePost ($title = null, $body = null, $indentLevel = 0, $class = 'art-Post') {
		$post = '';
		$title = trim($title);
		$body = trim($body);

		// if we don't have any information, just return
		if (empty($title) && empty($body)) {
			return $post;
		}

		$baseTabs = str_repeat("\t", $indentLevel);

		// start building the div string
		$post .= "{$baseTabs}<div class=\"{$class}\">\n";

		// add the header
		if (!empty($title)) {
			$post .= "{$baseTabs}\t<div class=\"{$class}Header\">\n";
			$post .= "{$baseTabs}\t\t{$title}\n";
			$post .= "{$baseTabs}\t</div><!-- end div.{$class}Header -->\n";
		}

		// add the body
		if (!empty($body)) {
			$post .= "{$baseTabs}\t<div class=\"{$class}Content\">\n";
			//@todo get rid of this in favor of a jw version
			$post .= "{$baseTabs}\t\t" . artxReplaceButtons($body) . "\n";
			$post .= "{$baseTabs}\t</div><!-- end div.{$class}Content -->\n";
			$post .= "{$baseTabs}\t<div class=\"cleared\"></div>\n";
		}
		$post .= "{$baseTabs}</div><!-- end div.{$class} -->\n";

		return $post;
	}

	/**
	 * checks to see whether the passed user is allowed to edit
	 * the given page
	 *
	 * @param JUser $user the page's user object
	 * @return BOOL
	 */
	public static function canEdit (JUser $user) {
		if (
			$user->authorize('com_content', 'edit', 'content', 'all')
			|| $user->authorize('com_content', 'edit', 'content', 'own')
		) {
			return true;
		}
		return false;
	}

	/**
	 * Starts a collection of code chunks to be output later on after being
	 * given some parameters
	 *
	 * @see JW::addChunk()
	 * @see JW::endChunks()
	 * @param STRING $header
	 * @return STRING
	 */
	public static function startChunks ($header) {
		$chunk = ChunkHelper::startNew();
		$chunk->setHeader($header);
		return $chunk->getId();
	}

	/**
	 * Adds a chunk to the chunk list
	 *
	 * @param STRING $key the key to the chunks
	 * @param STRING $string the next in the set of chunks
	 * @return VOID
	 */
	public static function addChunk ($key, $string) {
		ChunkHelper::get($key)->addChunk($string);
	}

	/**
	 * Adds a footer to the chunks
	 *
	 * @param STRING $key the key to the chunks
	 * @param STRING $string the footer for the chunks
	 * @return VOID
	 */
	public static function endChunks ($key, $string) {
		ChunkHelper::get($key)->setFooter($string);
	}

	/**
	 * Constructs a string using the header, chunks, and footer. Separates
	 * each chunk by $separator
	 *
	 * @param STRING $key the key to the chunks
	 * @param STRING $separator string with which to to implode the chunks
	 * @return STRING
	 */
	public static function getChunks ($key, $separator) {
		return ChunkHelper::get($key)->getComposite($separator);
	}

	/**
	 * removes the chunks from the static cache so they can't be used again
	 *
	 * @param STRING $key the key to the chunks
	 * @return VOID
	 */
	public static function clearChunks ($key) {
		ChunkHelper::clear($key);
	}

	/**
	 * The same as calling getChunks($key, $separator); clearChunks($key)
	 *
	 * @param STRING $key the key to the chunks
	 * @param STRING $separator string with which to to implode the chunks
	 * @return STRING
	 */
	public static function getAndClearChunks ($key, $separator) {
		$output = self::getChunks($key, $separator);
		self::clearChunks($key);
		return $output;
	}

	/**
	 * Gets the HTML string created by Joomla for a particular
	 * icon after determining that the requested icon is a valid
	 * type.
	 *
	 * @see JW::isValidIconType()
	 * @param STRING $iconType the name of the icon
	 * @return STRING
	 */
	public static function getIcon ($iconType, $page) {
		if (!self::isValidIconType($iconType)) {
			return '';
		}
		$iconMethod = "icon.{$iconType}";
		return JHTML::_($iconMethod, $page->item, $page->item->params, $page->access);
	}

	/**
	 * Checks to see if the provided icon type is a valid type
	 *
	 * @param STRING $type the type of icon
	 * @return BOOL
	 */
	private static function isValidIconType ($type) {
		return in_array($type, self::$validIconTypes);
	}
}
