<?php
defined('_JEXEC') or die('Restricted access'); // no direct access

if (!defined('_ARTX_FUNCTIONS'))
	require_once dirname(__FILE__) . str_replace('/', DIRECTORY_SEPARATOR, '/../functions.php');

function modChrome_artstyle($module, &$params, &$attribs)
{
	$style = isset($attribs['artstyle']) ? $attribs['artstyle'] : 'art-nostyle';
	$sfx = $params->get('moduleclass_sfx');
	if (in_array($sfx, array('art-nostyle', 'art-block', 'art-article')))
		$style = $sfx;
	switch ($style) {
		case 'art-block':
			modChrome_artblock($module, $params, $attribs);
			break;
		case 'art-article':
			modChrome_artarticle($module, $params, $attribs);
			break;
		case 'art-nostyle':
			modChrome_artnostyle($module, $params, $attribs);
			break;
	}
}

function modChrome_artnostyle($module, &$params, &$attribs)
{
	$outputStr = '';
	if (!empty ($module->content)) {
		$outputStr .= '<div class="art-nostyle">' . "\n";
		if ($module->showtitle != 0) {
			$outputStr .= "	<h3>{$module->title}</h3>\n";
		}
		$outputStr .= "	{$module->content}\n</div>";
	}
	echo $outputStr;
}

function modChrome_artblock($module, &$params, &$attribs)
{
	if (!empty ($module->content)) {
		echo artxBlock(($module->showtitle != 0) ? $module->title : '', $module->content);
	}
}

function modChrome_artarticle($module, &$params, &$attribs)
{
	if (!empty ($module->content)) {
		echo artxPost(($module->showtitle != 0) ? $module->title : '', $module->content);
	}
}
