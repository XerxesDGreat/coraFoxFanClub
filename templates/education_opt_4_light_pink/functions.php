<?php
//defined('_JEXEC') or die('Restricted access'); // no direct access

if (!defined('_ARTX_FUNCTIONS')) {

	define('_ARTX_FUNCTIONS', 1);

	function artxHasMessages()
	{
		global $mainframe;
		$messages = $mainframe->getMessageQueue();
		if (is_array($messages) && count($messages))
			foreach ($messages as $msg)
				if (isset($msg['type']) && isset($msg['message']))
					return true;
		return false;
	}

	function artxUrlToHref($url)
	{
		$result = '';
		$p = parse_url($url);
		if (isset($p['scheme']) && isset($p['host'])) {
			$result = $p['scheme'] . '://';
			if (isset($p['user'])) {
				$result .= $p['user'];
				if (isset($p['pass']))
					$result .= ':' . $p['pass'];
				$result .= '@';
			}
			$result .= $p['host'];
			if (isset($p['port']))
				$result .= ':' . $p['port'];
			if (!isset($p['path']))
				$result .= '/';
		}
		if (isset($p['path']))
			$result .= $p['path'];
		if (isset($p['query'])) {
			$result .= '?' . str_replace('&', '&amp;', $p['query']);
		}
		if (isset($p['fragment']))
			$result .= '#' . $p['fragment'];
		return $result;
	}

	function artxReplaceButtonsRegex() {
		return '' .
			'~<input\b[^>]*'
				. '(?:'
					. '[^>]*\bclass=(?:"(?:[^"]*\s)?button(?:\s[^"]*)?"|\'(?:[^\']*\s)?button(?:\s[^\']*)?\'|button\b)[^>]*'
					. '(?:\bvalue=(?:"[^"]*"|\'[^\']*\'|[^>\s]*))'
				. '|'
					. '(?:\bvalue=(?:"[^"]*"|\'[^\']*\'|[^>\s]*))'
					. '[^>]*\bclass=(?:"(?:[^"]*\s)?button(?:\s[^"]*)?"|\'(?:[^\']*\s)?button(?:\s[^\']*)?\'|button\b)[^>]*'
				. '|'
					. '[^>]*\bclass=(?:"(?:[^"]*\s)?button(?:\s[^"]*)?"|\'(?:[^\']*\s)?button(?:\s[^\']*)?\'|button\b)[^>]*'
				. ')'
			. '[^>]*/?\s*>~i';
	}

	function artxReplaceButtons($content)
	{
		$re = artxReplaceButtonsRegex();
		if (!preg_match_all($re, $content, $matches, PREG_OFFSET_CAPTURE))
			return $content;

		$result = '';
		$position = 0;
		foreach ($matches[0] as $match) {
			$result .= substr($content, $position, $match[1] - $position);
			$position = $match[1] + strlen($match[0]);
			$result .= '<span class="art-button-wrapper"><span class="l"> </span><span class="r"> </span>'
				. preg_replace('~\bclass=(?:"([^"]*\s)?button(\s[^"]*)?"|\'([^\']*\s)?button(\s[^\']*)?\'|button\b)~i',
					'class="\1\3button art-button\2\4"', $match[0]) . '</span>';
		}
		$result .= substr($content, $position);
		return $result;
	}

	function artxPost($caption, $content, $class = 'art-Post')
	{
		return jwBuildPost ($caption, $content, $class);
	}

	/**
	 * construct a post div from the title and the body
	 * @param STRING $title the title of the post [null]
	 * @param STRING $body the body of the post [null]
	 * @param STRING $class the css class of the div set [art-Post]
	 * @param INT $nestingLevel how many indents (for good-looking HTML) [0]
	 * @return STRING the post div
	 */
	function jwBuildPost ($title = null, $body = null, $class = 'art-Post', $nestingLevel = 0) {
		$post = '';
		$title = trim($title);
		$body = trim($body);

		// if we don't have any information, just return
		if (empty($title) && empty($body)) {
			return $post;
		}

		$baseTabs = str_repeat("\t", $nestingLevel);

		// start building the div string
		$post .= "{$baseTabs}<div class=\"{$class}\">\n";
		if (!empty($title)) {
			$post .= "{$baseTabs}\t<div class=\"{$class}Header\">\n";
			$post .= "{$baseTabs}\t\t{$title}\n";
			$post .= "{$baseTabs}\t</div><!-- end div.{$class}Header -->\n";
		}
		if (!empty($body)) {
			$post .= "{$baseTabs}\t<div class=\"{$class}Content\">\n";
			$post .= "{$baseTabs}\t\t" . artxReplaceButtons($body) . "\n";
			$post .= "{$baseTabs}\t</div><!-- end div.{$class}Content -->\n";
			$post .= "{$baseTabs}\t<div class=\"cleared\"></div>\n";
		}
		$post .= "{$baseTabs}</div><!-- end div.{$class} -->\n";

		return $post;
	}

	function artxBlock($caption, $content)
	{
		$hasCaption = (null !== $caption && strlen(trim($caption)) > 0);
		$hasContent = (null !== $content && strlen(trim($content)) > 0);

		if (!$hasCaption && !$hasContent)
			return '';

		ob_start();
?>
		<div class="art-Block">
		<?php if ($hasCaption): ?>
			<div class="art-BlockHeader">
				<?php echo $caption; ?>
			</div><!-- end div.art-BlockHeader -->
		<?php endif; ?>
		<?php if ($hasContent): ?>
			<div class="art-BlockContent">
				<?php echo artxReplaceButtons($content); ?>
				<div class="cleared"></div>
			</div><!-- end div.art-BlockContent" -->
		<?php endif; ?>
			<div class="cleared"></div>
		</div>
		
<?php
		return ob_get_clean();
	}

	function artxPageTitle($page, $criteria = null, $key = null)
	{
		if ($criteria === null)
			$criteria = $page->params->def('show_page_title', 1);
		return $criteria
			? ('<span class="componentheading' . $page->params->get('pageclass_sfx') . '">'
				. $page->escape($page->params->get($key === null ? 'page_title' : $key)) . '</span>')
			: '';
	}
	
	function artxCountModules(&$document, $position)
	{
		if (null === $document)
			// for Joomla 1.0
			return mosCountModules($position);
		// for Joomla 1.5
		return $document->countModules($position);
	}

	// position constants
	define('ARTX_POS_RIGHT_HEAVY', 1);
	define('ARTX_POS_LEFT_HEAVY', 2);
	define('ARTX_POS_THREE_BALANCED', 4);
	define('ARTX_POS_HALF', 8);
	define('ARTX_POS_SINGLE', 16);
	define('ARTX_POS_NONE', 32);
	function artxGetPosType (&$document, $positions, $style) {
		$numPositions = 2;
		if (count($positions) == 3) {
			$numPositions ++;
		}
		$display = array();

		for ($i = 0; $i < $numPositions; $i ++) {
			if (artxCountModules($document, $positions[$i])) {
				$display[$i] = artxModules($document, $positions[$i], $style);
			} else {
				$display[$i] = false;
			}
		}

		$show = ARTX_POS_NONE;
		if (
			// check if we're splitting in half
			($numPositions == 2 && $display[0] && $display[1])
			|| ($numPositions == 3 && $display[0] && $display[2] && !$display[1])
		) {
			$show = ARTX_POS_HALF;
		} else if (
			// check if we have three
			($numPositions == 3 && $display[0] && $display[1] && $display[2])
		) {
			$show = ARTX_POS_THREE_BALANCED;
		} else if (
			// check if we're weighting the left
			($numPositions == 3 && $display[0] && $display[1])
		) {
			$show = ARTX_POS_LEFT_HEAVY;
		} else if (
			// check if we're weighting the right
			($numPositions == 3 && $display[1] && $display[2])
		) {
			$show = ARTX_POS_RIGHT_HEAVY;
		} else if (
			// if any of them contain info
			$display[0] || $display[1] || (isset($display[2]) && $display[2])
		) {
			$show = ARTX_POS_SINGLE;
		}

		foreach ($display as $index => $item) {
			if (!$item) {
				unset($display[$index]);
			}
		}

		$retVal = array(
			'type' => $show,
			'displays' => array_values($display)
		);

		return $retVal;
	}
	
	function artxPositions(&$document, $positions, $style)
	{
		$posType = artxGetPosType($document, $positions, $style);
		$output = '';

		switch ($posType['type']) {
			case ARTX_POS_HALF:
			case ARTX_POS_THREE_BALANCED:
			case ARTX_POS_SINGLE:
			default:
				if ($posType['type'] == ARTX_POS_HALF) {
					$divClass = 'one-half';
				} else if ($posType['type'] == ARTX_POS_THREE_BALANCED) {
					$divClass = 'one-third';
				} else {
					$divClass = 'full-width';
				}

				$divClass = 'art-' . $divClass;

				foreach ($posType['displays'] as $display) {
					$output .= "\t" . '<div class="' . $divClass . '">' . "\n";
					$output .= "\t\t" . $display . "\n";
					$output .= "\t" . '</div><!-- end div.' . $divClass . '-->' . "\n";
				}
				break;
			case ARTX_POS_RIGHT_HEAVY:
			case ARTX_POS_LEFT_HEAVY:
				if ($posType['type'] == ARTX_RIGHT_HEAVY) {
					$firstClass = 'art-one-third';
					$secondClass = 'art-two-thirds';
				} else {
					$firstClass = 'art-two-thirds';
					$secondClass = 'art-one-third';
				}
				$output .= "\t" . '<div class="' . $firstClass . '">' . "\n"
					. "\t\t" . $posType['displays'][0] . "\n"
					. "\t" . '</div><!-- end div.' . $firstClass . '-->' . "\n"
					. '<div class="' . $secondClass . '">' . "\n"
					. "\t\t" . $posType['displays'][1] . "\n"
					. "\t" . '</div><!-- end div.' . $secondClass . '-->' . "\n";
				break;
			case ARTX_POS_NONE:
				// no output
				break;
		}

		if ($posType['type'] != ARTX_POS_NONE) {
			$output = '<div class="position">' . "\n"
				. "\t" . $output . "\n"
				. '</div><!-- end div.position -->' . "\n";
		}

		return $output;
	}
	
	function artxGetContentCellStyle(&$document)
	{
		$leftCnt = artxCountModules($document, 'left');
		$rightCnt = artxCountModules($document, 'right');
		if ($leftCnt > 0 && $rightCnt > 0)
			return 'content';
		if ($rightCnt > 0)
			return 'content-sidebar1';
		if ($leftCnt > 0)
			return 'content-sidebar2';
		return 'content-wide';
	}
	
	function artxHtmlFixMoveScriptToHead($re, $content)
	{
		if (preg_match($re, $content, $matches, PREG_OFFSET_CAPTURE)) {
			$content = substr($content, 0, $matches[0][1])
				. substr($content, $matches[0][1] + strlen($matches[0][0]));
			$document =& JFactory::getDocument();
			$document->addScriptDeclaration($matches[1][0]); 
		}
		return $content;
	}

	function artxHtmlFixFormAction($content)
	{
		if (preg_match('~ action="([^"]+)" ~', $content, $matches, PREG_OFFSET_CAPTURE)) {
			$content = substr($content, 0, $matches[0][1])
				. ' action="' . artxUrlToHref($matches[1][0]) . '" '
				. substr($content, $matches[0][1] + strlen($matches[0][0]));
		}
		return $content;
	}

	function artxHtmlFixRemove($re, $content)
	{
		if (preg_match($re, $content, $matches, PREG_OFFSET_CAPTURE)) {
			$content = substr($content, 0, $matches[0][1])
				. substr($content, $matches[0][1] + strlen($matches[0][0]));
		}
		return $content;
	}

	function artxComponentWrapper(&$document, $usePost = true)
	{
		if (null === $document) {
			// for Joomla 1.0
			return;
		}
		// for Joomla 1.5
		if ($document->getType() != 'html') return;
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$layout = JRequest::getCmd('layout');
		$content = $document->getBuffer('component');

		// fixes for w3.org validation
		if ('com_contact' == $option) {
			if ('category' == $view) {
				$content = artxHtmlFixFormAction($content);
			} elseif ('contact' == $view) {
				$content = artxHtmlFixMoveScriptToHead('~<script [^>]+>\s*(<!--[^>]*-->)\s*</script>~', $content);
			}
		} elseif ('com_content' == $option) {
			if ('category' == $view) {
				if ('' == $layout) {
					$content = artxHtmlFixMoveScriptToHead('~<script [^>]+>([^<]*)</script>~', $content);
					$content = artxHtmlFixFormAction($content);
				}
			} elseif ('archive' == $view) {
				$content = artxHtmlFixRemove('~<ul id="archive-list" style="list-style: none;">\s*</ul>~', $content);
			}
		} elseif ('com_user' == $option) {
			if ('user' == $view) {
				if ('form' == $layout) {
					$content = artxHtmlFixRemove('~autocomplete="off"~', $content);
				}
			}
		}
		if (false === strpos($content, '<div class="art-Post">') && $usePost) {
			$title = null;

			if (preg_match('~<div\s+class="(componentheading[^"]*)"([^>]*)>([^<]+)</div>~', $content, $matches, PREG_OFFSET_CAPTURE)) {
				$content = substr($content, 0, $matches[0][1]) . substr($content, $matches[0][1] + strlen($matches[0][0]));
				$title = '<span class="' . $matches[1][0] . '"' . $matches[2][0] . '>' . $matches[3][0] . '</span>';
			}
			$document->setBuffer(artxPost($title, $content), 'component');
		} else {
			$document->setBuffer($content, 'component');
		}
	}
	
	function artxComponent()
	{
		// for Joomla 1.0
		ob_start();
		mosMainBody();
		$content = ob_get_clean();
		if (false === strpos($content, '<div class="art-Post">')) {
			$title = null;
			if (preg_match('~<div\s+class="(componentheading[^"]*)"([^>]*)>([^<]+)</div>~', $content, $matches, PREG_OFFSET_CAPTURE)) {
				$content = substr($content, 0, $matches[0][1]) . substr($content, $matches[0][1] + strlen($matches[0][0]));
				$title = '<span class="' . $matches[1][0] . '"' . $matches[2][0] . '>' . $matches[3][0] . '</span>';
			}
			return artxPost($title, $content);
		}
		return $content;
	}
	
	function artxModules(&$document, $position, $style = null)
	{
		if (null === $document) {
			// for Joomla 1.0
			ob_start();
			mosLoadModules($position, -2);
			$content = ob_get_clean();
			if (null == $style || 'xhtml' == $style)
				return $content;
			$decorator = 'artblock' == $style ? 'artxBlock' : ('artpost' == $style ? 'artxPost' : null);
			$result = '';
			$modules = preg_split('~</div>\s*<div class="moduletable">~', $content);
			$lastModule = count($modules) - 1;
			if ($lastModule > -1) {
				$modules[0] = preg_replace('~^\s*<div class="moduletable">~', '', $modules[0]);
				$modules[$lastModule] = preg_replace('~</div>\s*$~', '', $modules[$lastModule]);
				foreach ($modules as $module) {
					if (preg_match('~^\s*<h3>([^<]*)</h3>~', $module, $matches, PREG_OFFSET_CAPTURE)) {
						$result .= $decorator($matches[1][0], substr($module, 0, $matches[0][1])
							. substr($module, $matches[0][1] + strlen($matches[0][0])));
					} else {
						$result .= $decorator(null, $module);
					}
				}
			}
			return $result;
		}
		// for Joomla 1.5
		return '<jdoc:include type="modules" name="' . $position . '"' . (null != $style ? ' style="artstyle" artstyle="' . $style . '"' : '') . ' />';
	}
	
	$artxFragments = array();

	function artxFragmentBegin($head = '')
	{
		global $artxFragments;
		$artxFragments[] = array('head' => $head, 'content' => '', 'tail' => '');
	}

	function artxFragmentContent($content = '')
	{
		global $artxFragments;
		$artxFragments[count($artxFragments) - 1]['content'] = $content;
	}

	function artxFragmentEnd($tail = '', $separator = '')
	{
		global $artxFragments;
		$fragment = array_pop($artxFragments);
		$fragment['tail'] = $tail;
		$content = trim($fragment['content']);
		if (count($artxFragments) == 0) {
			echo (trim($content) == '') ? '' : ($fragment['head'] . $content . $fragment['tail']);
		} else {
			$result = (trim($content) == '') ? '' : ($fragment['head'] . $content . $fragment['tail']);
			$fragment =& $artxFragments[count($artxFragments) - 1];
			$fragment['content'] .= (trim($fragment['content']) == '' ? '' : $separator) . $result;
		}
	}

	function artxFragment($head = '', $content = '', $tail = '', $separator = '')
	{
		global $artxFragments;
		if ($head != '' && $content == '' && $tail == '' && $separator == '') {
			$content = $head;
			$head = '';
		} elseif ($head != '' && $content != '' && $tail == '' && $separator == '') {
			$separator = $content;
			$content = $head;
			$head = '';
		}
		artxFragmentBegin($head);
		artxFragmentContent($content);
		artxFragmentEnd($tail, $separator);
	}

	function artxShouldAllowEdit ($page) {
		return (($page->user->authorize('com_content', 'edit', 'content', 'all')
			|| $page->user->authorize('com_content', 'edit', 'content', 'own'))
			&& !($page->print));
	}

	function artxIndent ($string, $level = 0) {
		$retString = '';
		if ($level > 0) {
			$retString .= str_repeat("\t", $level);
		}
		$retString .= $string . "\n";

		return $retString;
	}

}
