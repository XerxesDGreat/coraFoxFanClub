<?php
/**
* @file
* @brief    sigplus Image Gallery Plus save and restore settings control
* @author   Levente Hunyadi
* @version  1.3.4
* @remarks  Copyright (C) 2009-2011 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/sigplus
*/

/*
* sigplus Image Gallery Plus plug-in for Joomla
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

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

// This file is fully compatible with PHP 4.

/**
* Renders a control for saving and restoring parameters.
* This class implements a user-defined control in the administration backend.
*/
class JElementBackup extends JElement {
	/**
	* Element type.
	*/
	var $_name = 'backup';

	/*public*/ function fetchElement($name, $value, &$node, $control_name) {
		$class = ( $node->attributes('class') ? $node->attributes('class') : 'inputbox' );

		// add jQuery
		$document =& JFactory::getDocument();
		if (version_compare(JVERSION, '1.5.19') < 0 || version_compare(JVERSION, '1.6') < 0 && !JPluginHelper::isEnabled('system', 'mtupgrade')) {
			$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
			$document->addScript(JURI::root(true).'/plugins/content/sigplus/fields/backup.jquery.js');
		} else {
			$document->addScript(JURI::root(true).'/plugins/content/sigplus/fields/backup.js');
		}

		// add control to page
		$ctrlname = $control_name.'['.$name.']';
		$ctrlid = str_replace(array('[',']'), '', $ctrlname);

		return
			'<button type="button" id="extension-settings-backup" class="settings-backup">'.JText::_('SIGPLUS_SETTINGS_BACKUP').'</button>'.
			'<button type="button" id="extension-settings-restore" class="settings-restore">'.JText::_('SIGPLUS_SETTINGS_RESTORE').'</button>'.
			'<br /><textarea id="extension-settings-list" rows="10" cols="40"></textarea>';
	}
}

