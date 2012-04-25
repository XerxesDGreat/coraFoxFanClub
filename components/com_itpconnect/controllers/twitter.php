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
 * VITPConnect Controller
 *
 * @package     ITPrism Components
 * @subpackage  ITPConnect
  */
class TwitterControllerItpConnect extends JController {
    
    // Check the table in so it can be edited.... we are done with it anyway
    private    $defaultLink = 'index.php?option=com_itpconnect&controller=twitter&view=twitter';
    
    public function __construct($config = array())  {
        parent::__construct($config);
    }

    public function display( ) {

        $document =& JFactory::getDocument();
        
        /* @var $document JDocument */
        $viewType = $document->getType();
        
        // Get layout value
        $viewLayout =   JRequest::getVar('layout', 'default');
        // Get view value
        $viewName   =   JRequest::getVar('view', 'Twitter');
        
        // Get view
        $view       =   $this->getView( $viewName, $viewType, "View" );
        
        // Get model
        $model      =   $this->getModel( $viewName , "Model");
        if (!JError::isError( $model ) ) { // Set model to view
            $view->setModel( $model, true );
        }
        
        // Set layout into view 
        $view->setLayout($viewLayout);
        
        try {
            // Display view
            $view->display();
        } catch ( Exception $e ) {
            
            $itpSecurity = new ItpSecurity( $e );
            $itpSecurity->AlertMe();
           
            JError::raiseError( 500, JText::_( 'ITP_ERROR_SYSTEM' ) );
            jexit( JText::_( 'ITP_ERROR_SYSTEM' ) );
            
        }
        
    }

}