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
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.helper');
jimport('joomla.plugin.plugin');

/**
* ITPConnect plugin
*
* @package 		ITPrism Plugins
* @subpackage	ITP Connect
*/
class plgUserITPConnect extends JPlugin {

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	public function plgUserITPConnectLogout(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * Set the indicator for logout.
	 * I will use the indicator for excetuting the a Facebook function 
	 * that will destroy the session.
	 *
	 * @access public
	 * @param array holds the user data
	 * @return boolean True on success
	 */
	public function onLogoutUser($user) {
        
	    $itpConnectParams = JComponentHelper::getParams('com_itpconnect');
        if(!$itpConnectParams->get("facebookOn", 0)){
            return true;
        }
        
		if (!JComponentHelper::isEnabled('com_itpconnect', true)) {
            return true;
        }

        $app =& JFactory::getApplication();
        /* @var $app JApplication */

        if($app->isAdmin()) {
            return true;
        }
		
        $fbSession  = ItpcHelper::getFB()->getSession();
        $fbUserId   = JArrayHelper::getValue($fbSession,"uid");

	   try {
            ItpcHelper::fbLogout($fbUserId);
        } catch (Exception $e) {
            $itpSecurity = new ItpSecurity( $e );
            $itpSecurity->AlertMe();
            JError::raiseError(500, JText::_('ITP_ERROR_SYSTEM'));
            jexit();
        }
		
		return true;
	}
	
    /**
     * Remove an user from ITPConnect users table
     *
     * Method is called before user data is deleted from the database
     *
     * @param   array       holds the user data
     */
    public function onBeforeDeleteUser($user) {
        
        $app =& JFactory::getApplication();
        /* @var $app JApplication */

        if(!$app->isAdmin()) {
            return;
        }
        $userId = JArrayHelper::getValue($user,"id",null);
        
        try {
            
            if(!$userId) {
                throw new ItpException("Invalid user ID.", 500);
            }
            
            // Initialize some variables
            $db = & JFactory::getDBO();
            
            $query = "
                DELETE FROM
                    `#__itpc_users` 
                WHERE
                    `users_id`=" . (int)$userId . "
                LIMIT
                    1";
                   
            $db->setQuery($query);
            $db->query();
            
            if ($db->getErrorNum() != 0) {
               throw new ItpException($db->getErrorMsg() , 500);
            }
            
        } catch (Exception $e) {
            $itpSecurity = new ItpSecurity($e);
            $itpSecurity->AlertMe();
            JError::raiseError(500, JText::_('ITP_ERROR_SYSTEM'));
            jexit();
        }
        
    }
}
