<?php
/**
 * @package      ITPrism Components
 * @subpackage   ITPConnect
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPConnect is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.html.pane' );
jimport ( 'joomla.application.component.view' );

class ViewSettings extends JView {
	
	public function display($tpl = null) {
		
		global $mainframe, $option;
		
		$model  = $this->getModel ();
		$layout = $this->getLayout ();
		
		$componentParams = &JComponentHelper::getParams( 'com_itpconnect' );
		
		// Gets a parameters description 
		$paramsDescFile = JPATH_COMPONENT_ADMINISTRATOR.DS.'params.xml';
		$params = new JParameter( $componentParams->toString(), $paramsDescFile );
		
		// Load a JEditor object
		$editor = JFactory::getEditor ();
		$this->assignRef ( 'editor', $editor );
		
		// Loads MooTools library
		JHTML::_('behavior.mootools');
		
		// Loads the behaviors
		JHTML::_ ( 'behavior.tooltip' );
		
		// The title of the page
		$this->assign ( 'title', JText::_ ( 'Settings' ) );
		$this->assignRef ( 'params', $params );
		
		parent::display ( $tpl );
	}

}