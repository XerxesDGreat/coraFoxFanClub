<?php
/*
 * JPlayer for Joomla! 1.5
 * Author: MaxSVK
 * Version: 1.5.4
 * Last Update: 2011-05-29
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementHeader extends JElement
{
	var $_name = 'header';

	function fetchElement($name, $value, &$node, $control_name)
	{
		// Output
		return '
		<div style="width:95%; font-weight:normal; font-size:12px; color:#ffffff; padding:4px; margin:0; background:#0B55C4;">
			'.JText::_($value).'
		</div>
		';
	}
	
}
?>
