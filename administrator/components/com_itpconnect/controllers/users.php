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

jimport( 'joomla.error.log' );
jimport( 'joomla.application.component.controller' );

/**
 * ITPConnect Users Controller
 *
 * @package     ITPrism Components
 * @subpackage  ITPConnect
  */
class UsersControllerItpConnect extends JController {
    
	// Check the table in so it can be edited.... we are done with it anyway
    private    $defaultLink = 'index.php?option=com_itpconnect&controller=users&view=users';
    
    public function __construct($config = array())  {
        parent::__construct($config);

//       Register Extra tasks
        $this->registerTask( 'add'  ,   'display' );
        $this->registerTask( 'edit' ,   'display' );
        $this->registerTask( 'apply',   'save'    );
        
    }

    public function display( ) {

        $document =& JFactory::getDocument();
        /* @var $document JDocument */
        
        // Add component style
        $document->addStyleSheet( JURI::base() . 'components/com_itpconnect/assets/css/style.css', 'text/css', null);
        
        $viewType = $document->getType();
        
        // Set add and edit task parameters
        switch($this->getTask())
        {
            case 'add'     :
            {
                JRequest::setVar( 'hidemainmenu', 1 );
                JRequest::setVar( 'layout', 'form'  );

            } 
            break;
            case 'edit'    :
            {
                JRequest::setVar( 'hidemainmenu', 1 );
                JRequest::setVar( 'layout', 'form'  );

            } 
            break;
            
        }
        
        // Get layout value
        $viewLayout =   JRequest::getVar('layout', 'default');
        // Get view value
        $viewName   =   JRequest::getVar('view', $this->_name );
        
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
            jexit(JText::_( 'ITP_ERROR_SYSTEM' ));
            
        }
        
    }


    /**
     * Remove items
     *
     */
    public function remove() {
        
    	$msg = "";
    	
        // Checks token
        if(!JRequest::getVar(JUtility::getToken(), false, 'POST'))  {
            JError::raiseError(500, JText::_( 'ITP_ERROR_REQUEST_FORBIDDEN' ));
        }
        
        $cids = JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($cids);
        
        try {
            
	        // Gets the model
	        $model =& $this->getModel( $this->_name , "Model");
	        $model->delete( $cids );
            
            $msg = JText::_( 'COM_ITPCONNECT_USERS_DELETED' );

        } catch ( ItpUserException $e ) {
            
            $msg = "";
            JError::raiseWarning( 404, $e->getMessage() );
            
        } catch ( Exception $e ) {
            
            $itpSecurity = new ItpSecurity( $e );
            $itpSecurity->AlertMe();
           
            JError::raiseError( 500, JText::_( 'ITP_ERROR_SYSTEM' ) );
            jexit(JText::_( 'ITP_ERROR_SYSTEM' ));
            
        }
        
        $this->setRedirect( $this->defaultLink, $msg );
        
    }
    
}