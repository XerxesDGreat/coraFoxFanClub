<?php
/**
* @file
* @brief    sigplus Image Gallery Plus module for Joomla
* @author   Levente Hunyadi
* @version  1.3.4
* @remarks  Copyright (C) 2009-2011 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/sigplus
*/

/*
* sigplus Image Gallery Plus module for Joomla
* Copyright 2009-2010 Levente Hunyadi
*
* sigplus is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* sigplus is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if (version_compare(PHP_VERSION, '5.1.0') < 0) {
	$errormsg = '<p><strong>[sigplus] Critical error:</strong> sigplus requires PHP version 5.1 or later.</p>';
	$app = JFactory::getApplication();
	$app->enqueueMessage($errormsg, 'error');

	$galleryhtml = $errormsg;
	require JModuleHelper::getLayoutPath('mod_sigplus');
	return;
}

if (!defined('SIGPLUS_VERSION_MODULE')) {
	define('SIGPLUS_VERSION_MODULE', '1.3.4');
}

if (!defined('SIGPLUS_DEBUG')) {
	// Triggers debug mode. Debug uses uncompressed version of scripts rather than the bandwidth-saving minified versions.
	define('SIGPLUS_DEBUG', false);
}
if (!defined('SIGPLUS_LOGGING')) {
	// Triggers logging mode. Verbose status messages are printed to the output.
	define('SIGPLUS_LOGGING', false);
}

// include the plug-in core file
$import = JPATH_PLUGINS.DS.'content'.DS.'sigplus'.DS.'core.php';
if (!is_file($import)) {
	$errormsg = '<p><strong>[sigplus] Critical error:</strong> <kbd>mod_sigplus</kbd> (sigplus module) requires <kbd>plg_sigplus</kbd> (sigplus plug-in) to be installed. The latest version of <kbd>plg_sigplus</kbd> is available from <a href="http://joomlacode.org/gf/project/sigplus/frs/">JoomlaCode</a>.</p>';
	$app = JFactory::getApplication();
	$app->enqueueMessage($errormsg, 'error');

	$galleryhtml = $errormsg;
	require JModuleHelper::getLayoutPath("mod_sigplus");
	return;
}
require_once $import;

if (!defined('SIGPLUS_VERSION') || !defined('SIGPLUS_VERSION_MODULE') || SIGPLUS_VERSION !== SIGPLUS_VERSION_MODULE) {
	$errormsg = '<p><strong>[sigplus] Critical error:</strong> <kbd>mod_sigplus</kbd> (sigplus module) requires a matching version of <kbd>plg_sigplus</kbd> (sigplus plug-in) to be installed. Currently you have <kbd>mod_sigplus</kbd> version '.SIGPLUS_VERSION_MODULE.' but your version of <kbd>plg_sigplus</kbd> is '.SIGPLUS_VERSION.'. The latest version of <kbd>plg_sigplus</kbd> and <kbd>mod_sigplus</kbd> is available from <a href="http://joomlacode.org/gf/project/sigplus/frs/">JoomlaCode</a>.</p>';
	$app = JFactory::getApplication();
	$app->enqueueMessage($errormsg, 'error');

	$galleryhtml = $errormsg;
	require JModuleHelper::getLayoutPath('mod_sigplus');
	return;
}

// include the helper file
require 'helper.php';

// include the template for display
require JModuleHelper::getLayoutPath('mod_sigplus');