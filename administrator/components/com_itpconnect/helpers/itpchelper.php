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
defined('_JEXEC') or die();

/**
 * It is the component helper class
 *
 */
class ItpcHelper {
    
    /**
     * Get a return URL for the current page
     * 
     * @return string   Return page
     */
    public static function getReturn(){
        
        $module = JModuleHelper::getModule("itpconnect");
        $return = "";
        
        if(!empty($module->params)){
            
            $params = new JParameter($module->params);
            $type   = ItpcHelper::getType();
            $return = ItpcHelper::getReturnURL($params, $type);
            $return = base64_decode($return);
        }
        
        if(!$return OR !JURI::isInternal($return)){
            $return = "/";
        }
        
        return $return;
    }
    
    public static function getReturnURL($params, $type){
        
        if($itemid = $params->get($type)){
            $menu = JSite::getMenu();
            $item = $menu->getItem($itemid);
            
            if($item){
                $url = JRoute::_($item->link . '&Itemid=' . $itemid, false);
            }else{
                // stay on the same page
                $uri = JFactory::getURI();
                $url = $uri->toString(array('path', 'query', 'fragment'));
            }
        
        }else{
            // stay on the same page
            $uri = JFactory::getURI();
            $url = $uri->toString(array('path', 'query', 'fragment'));
        }
        
        return base64_encode($url);
    }
    
    public static function getType(){
        $user = & JFactory::getUser();
        return (!$user->get('guest')) ? 'logout' : 'login';
    }
    
    /**
     * Create an istanse of Facebook object
     * 
     * @return Facebook
     */
    public static function getFB(){
        
        static $instance;
        
        if(!is_object($instance)){
            
            jimport('joomla.application.component.helper');
            
            // Gets parameters
            $params = JComponentHelper::getParams('com_itpconnect');
            
            // Create our Application instance (replace this with your appId and secret).
            $instance = new Facebook(array(
                'appId'  => $params->get("facebookAppId"), 
                'secret' => $params->get("facebookSecret"), 
                'cookie' => true
            ));
        }
        
        return $instance;
    
    }
    
    /**
     * Load Joomla User Id by e-mail
     * 
     * @param string $email
     * @return User Id 
     */
    public static function getJUserIdByEmail($email){
        
        settype($email, "string");
        
        // Initialize some variables
        $db = & JFactory::getDBO();
        
        $query = "
            SELECT 
               `id` 
            FROM 
               `#__users` 
            WHERE 
               `email` = " . $db->Quote($email);
        
        $db->setQuery($query, 0, 1);
        $result = $db->loadResult();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
        
        return $result;
    
    }
    
    /**
     * Loads Joomla User Id by Facebook id
     * 
     * @param integer Facebook Id
     * @return User Id 
     */
    public static function getJUserId($fbId){
        
        settype($fbId, "string");
        
        // Initialize some variables
        $db = & JFactory::getDBO();
        
        $query = "
            SELECT 
                `users_id` 
            FROM 
               `#__itpc_users` 
            WHERE 
               `fbuser_id` = " . $db->Quote($fbId);
        
        $db->setQuery($query, 0, 1);
        $result = $db->loadResult();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
        
        return $result;
    
    }
    
    public function fbLogout($fbUserId){
        
        settype($fbUserId, "string");
        
        // Initialize some variables
        $db = & JFactory::getDBO();
        
        $query = "
            INSERT INTO
                `#__itpc_sessions` 
            SET
                `fbuser_id`=" . $db->Quote($fbUserId) . ",
                `logout`=1";
        
        $db->setQuery($query);
        $db->query();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
    }
    
    /**
     * Checks for logout
     * 
     * @param $userId
     * @return bool
     */
    public function checkLogout($fbUserId){
        
        settype($fbUserId, "string");
        
        $db = & JFactory::getDBO();
        /* @var $db JDatabaseMySQL */
        
        $query = "
           SELECT 
               `logout` 
           FROM 
               `#__itpc_sessions` 
           WHERE 
               `fbuser_id`=" . $db->Quote($fbUserId);
        
        $db->setQuery($query, 0, 1);
        $result = $db->loadResult();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
        
        return (bool)$result;
    }
    
    /**
     * Clear user session 
     * 
     * @param $userId
     */
    public function clearSession($fbUserId){
        
        settype($fbUserId, "string");
        
        // Initialize some variables
        $db = & JFactory::getDBO();
        
        $query = "
            DELETE FROM
                `#__itpc_sessions` 
            WHERE
                `fbuser_id`=" . $db->Quote($fbUserId) . "
            OR
                `date` <= DATE_SUB(CURRENT_TIMESTAMP,INTERVAL 1 DAY)";
        
        $db->setQuery($query);
        $db->query();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
    
    }
}