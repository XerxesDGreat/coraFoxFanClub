<?php
/**
 * @package      ITPrism Plugins
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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');
jimport('joomla.plugin.plugin');

/**
 * ITPConnect Authentication Plugin
 *
 * @package      ITPrism Plugins
 * @subpackage   ITP Connect
 */
class plgAuthenticationItpConnect extends JPlugin {
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param	object	$subject	The object to observe
     * @param	array	$config		An array that holds the plugin configuration
     * @since	1.5
     */
    public function plgAuthenticationItpConnect(& $subject, $config){
        parent::__construct($subject, $config);
    }
    
    /**
     * This method should handle any authentication and report back to the subject
     *
     * @access	public
     * @param	array	$credentials	Array holding the user credentials
     * @param	array	$options		Array of extra options
     * @param	object	$response		Authentication response object
     * @return	boolean
     * @since	1.5
     */
    public function onAuthenticate($credentials, $options, &$response){
        
        /*
		 * Here you would do whatever you need for an authentication routine with the credentials
		 *
		 * In this example the mixed variable $return would be set to false
		 * if the authentication routine fails or an integer userid of the authenticated
		 * user if the routine passes
		 */
        $success = true;
        
        $itpConnectParams = JComponentHelper::getParams('com_itpconnect');
        if(!$itpConnectParams->get("facebookOn")){
            return false;
        }
        
        if(! JComponentHelper::isEnabled('com_itpconnect', true)){
            return false;
        }
        
        $app = & JFactory::getApplication();
        /* @var $app JApplication */
        
        if($app->isAdmin()){
            return false;
        }
        
        $facebook = ItpcHelper::getFB();
        
        $session = $facebook->getSession();
        
        $me = null;
        // Session based API call.
        if($session){
            try{
                $me = $facebook->api('/me');
            }catch(FacebookApiException $e){
                $itpSecurity = new ItpSecurity($e);
                $itpSecurity->AlertMe();
                $me = null;
            }
        }
        
        if(!$me){
            $response->status        = JAUTHENTICATE_STATUS_FAILURE;
            $response->error_message = 'Could not authenticate';
            $success = false;
        }else{
            
            $userId 				 = ItpcHelper::getJUserId($me['id']);
            $user 					 = JUser::getInstance($userId); // Bring this in line with the rest of the system
            $response->email         = $user->email;
            $response->fullname      = $user->name;
            $response->status        = JAUTHENTICATE_STATUS_SUCCESS;
            $response->error_message = '';
            
            $success = true;
        }
        
        return $success;
    }
}
