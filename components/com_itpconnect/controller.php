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
defined( '_JEXEC' ) or die();

jimport( 'joomla.application.component.controller' );

class FacebookControllerItpConnect extends JController {
    
	// Check the table in so it can be edited.... we are done with it anyway
    private    $defaultLink = 'index.php?option=com_itpconnect&controller=facebook&view=facebook';
    
    function __construct($config = array()) {
	    
		parent::__construct($config);

	}
	
    public function display() {

        $document =& JFactory::getDocument();
        
        /* @var $document JDocument */
        $viewType = $document->getType();
        
        // Get layout value
        $viewLayout =   JRequest::getVar('layout', 'default');
        // Get view value
        $viewName   =   JRequest::getVar('view', $this->_name);
        
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
    
    public function connect(){
    	
        $facebook = ItpcHelper::getFB();
        $session  = $facebook->getSession();
        
        $me = null;
        // Session based API call.
        if ($session) {
          try {
            $uid = $facebook->getUser();
            $me  = $facebook->api('/me');
          } catch (FacebookApiException $e) {
            $itpSecurity = new ItpSecurity( $e );
            $itpSecurity->AlertMe();
            $me = null;
          }
        }
        
        if($me){
        	// Get model
            $model =   $this->getModel("Facebook", "Model");
        	$data  =   $model->getData($uid);

        	if (!$data){
            	try {
                    // Create new user or Connect existing user with the facebook profile 
                    $userId = $model->store($uid,$me);
                    $data   = $model->getData($uid);
                } catch ( Exception $e ) {
                    
                    $itpSecurity = new ItpSecurity( $e );
                    $itpSecurity->AlertMe();
                   
                    ItpResponse::sendJsonMsg("Error on user registration!",0);
                    jexit();
                    
                }
        		
        	}
        	
        	$user   = JUser::getInstance($data->users_id);
        	
        	$credentials['username'] = $user->get("username");
        	$credentials['password'] = $user->get("password");
        	
        	$options = array();
	        $options['remember']     = JRequest::getBool('remember', true);
	        $options['return']       = "";
        	
	        global $mainframe;
	        
        	//preform the login action
            $error = $mainframe->login($credentials, $options);
            
            if(!JError::isError($error)) {
            	ItpResponse::sendJsonMsg("All is OK",1);
            }else{
            	$itpSecurity = new ItpSecurity( $e );
                $itpSecurity->AlertMe();
                ItpResponse::sendJsonMsg("Error on login!",0);
	        }
            
        }
    	
    }

} 