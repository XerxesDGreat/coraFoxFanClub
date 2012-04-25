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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.controller' );

/**
 * Control Panel Controller
 *
 * @package		ITPrism Components
 * @subpackage	ITPConnect
  */
class CpControllerItpConnect extends JController {
    
	public function __construct($config = array())	{
		parent::__construct($config);
		
	}

	public function display( ) {

		$document =& JFactory::getDocument();
		// Add component style
        $document->addStyleSheet( JURI::base() . 'components/com_itpconnect/assets/css/style.css', 'text/css', null);
        
        /* @var $document JDocument */
        $viewType = $document->getType();
        
		// Get layout value
        $viewLayout =   JRequest::getCmd('layout', 'default');
        // Get view value
        $viewName   =   JRequest::getCmd('view', 'cpanel');
        
        // Get view
        $view       =   $this->getView( $viewName, $viewType, "View" );
        
        // Set layout into view 
        $view->setLayout($viewLayout);
        
        // Display view
        $view->display();
	}

}