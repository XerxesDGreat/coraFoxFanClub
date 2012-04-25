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

jimport( 'joomla.application.component.model' );

/**
 * It is a facebook model
 * 
 * @author Todor Iliev
 */
class ModelFacebook extends JModel {
    
    /**
     * Data array
     *
     * @var array
     */
    private $data = null;

    /**
     * Total items
     *
     * @var integer
     */
    private $total = null;

    /**
     * Pagination object
     *
     * @var object
     */
    private $pagination = null;
    
    public function  __construct() {
        parent::__construct();
    }
    
    /**
     * Method to get data
     *
     * @since 1.5
     */
    public function getData($fbUserId)  {

        settype($fbUserId,"string");
        $fbUserId = JString::trim($fbUserId);
        
        $query  =   "
          SELECT  
              *
          FROM 
              `#__itpc_users`
          WHERE
              `fbuser_id` = " . $this->_db->Quote($fbUserId);
    
        $this->_db->setQuery($query);
       
        $this->data = $this->_db->loadObject();
        
        if ($this->_db->getErrorNum() != 0) {
           throw new ItpException($this->_db->getErrorMsg() , 500);
        }
        
        return $this->data;
    }
    
    /**
     * Create a new user
     * 
     * @param $fbUserId  A Facebook User ID
     * 
     * @return     User id
     */
    public function store($fbUserId,$fbUserData) {
        
        settype($fbUserId,"string");
        $fbUserId = JString::trim($fbUserId);
        
        if (!$fbUserId) {
            throw new ItpException( JText::_( 'ITP_ERROR_FB_ID'), 404 );
        }
        
        $userId     =   ItpcHelper::getJUserIdByEmail($fbUserData['email']);
        
        $user       =   clone(JFactory::getUser());
        
        if(!$userId) {
            // Get required system objects
            $config     =& JFactory::getConfig();
            $authorize  =& JFactory::getACL();
            $document   =& JFactory::getDocument();
            
            // If user registration is not allowed, show 403 not authorized.
            $usersConfig = &JComponentHelper::getParams( 'com_users' );
            if ($usersConfig->get('allowUserRegistration') == '0') {
                throw new ItpException(JText::_( 'Access Forbidden' ), 403);
            }
            
            // Initialize new usertype setting
            $newUsertype = $usersConfig->get( 'new_usertype' );
            if (!$newUsertype) {
                $newUsertype = 'Registered';
            }
            
            jimport('joomla.user.helper');
            
            $userData['name']       = $fbUserData['name'];
            $userData['email']      = $fbUserData['email'];
            $userData['username']   = substr($fbUserData['email'], 0,strpos($fbUserData['email'],"@"));
            $userData['password']   = $password = JUserHelper::genRandomPassword();
            $userData['password2']  = $password;

            // Bind the post array to the user object
            if (!$user->bind( $userData, 'usertype' )) {
                throw new ItpException(JText::_( $user->getError()),500);
            }
            
            // Set some initial user values
            $user->set('id', 0);
            $user->set('usertype', $newUsertype);
            $user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));
            
            $date =& JFactory::getDate();
            $user->set('registerDate', $date->toMySQL());
            
            // If there was an error with registration, set the message and display form
            if (!$user->save()) {
                throw new ItpException(JText::_( $user->getError()),500);
            }
            
            // Send a confirmation mail
            $this->sendConfirmationMail($user, $password);
            
        } else {
            $user->load($userId);
        }
        
        // Loads a record from database
        $row        =   $this->getTable("users");
        $row->load($fbUserId,"facebook");
        
        // Initialize object for new record
        if(!$row->id) {
            $row = $this->getTable("users");
        }
        
        $row->set("users_id",   $user->id);
        $row->set("fbuser_id",  $fbUserId);
        
        if (!$row->store()) {
           throw new ItpException($row->getError() , 500);
        }
        
        return $row->users_id;
        
    }
    
    /**
     * Send confirmation e-mail
     * 
     * @param array  User data
     * @param string User password in raw format
     * 
     * @return bool True on success
     */
    private function sendConfirmationMail($user, $password) {
        
        $result = true;
        $app    = JFactory::getApplication();
        /* @var $app JApplication */
        
        $params = $app->getParams("com_itpconnect");
        
        if($params->get("fbSendConfirmationMail")) {
            
            global $mainframe;
    
            $db     =& JFactory::getDBO();
    
            $name       = $user->get('name');
            $email      = $user->get('email');
            $username   = $user->get('username');
    
            $sitename   = $mainframe->getCfg( 'sitename' );
            $mailfrom   = $mainframe->getCfg( 'mailfrom' );
            $fromname   = $mainframe->getCfg( 'fromname' );
            $siteURL    = JURI::base();
    
            $subject    = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
            $subject    = html_entity_decode($subject, ENT_QUOTES);
    
            $message    = sprintf ( JText::_( 'COM_ITPCONNECT_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY' ), $name, $siteURL, $username, $password);
            $message    = html_entity_decode($message, ENT_QUOTES);
    
            //get all super administrator
            $query = 'SELECT name, email, sendEmail' .
                    ' FROM #__users' .
                    ' WHERE LOWER( usertype ) = "super administrator"';
            $db->setQuery( $query );
            $rows = $db->loadObjectList();
    
            // Send email to user
            if ( ! $mailfrom  || ! $fromname ) {
                $fromname = $rows[0]->name;
                $mailfrom = $rows[0]->email;
            }
    
            $result = JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);
    
            // Send notification to all administrators
            $subject2 = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
            $subject2 = html_entity_decode($subject2, ENT_QUOTES);
    
            // get superadministrators id
            foreach ( $rows as $row )
            {
                if ($row->sendEmail)
                {
                    $message2 = sprintf ( JText::_( 'COM_ITPCONNECT_SEND_MSG_ADMIN' ), $row->name, $sitename, $name, $email, $username);
                    $message2 = html_entity_decode($message2, ENT_QUOTES);
                    $result = JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
                }
            }
        }
        
        return $result;
    }
    
}