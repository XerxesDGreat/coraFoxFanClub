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

defined('_JEXEC') or die('Restricted access');
class TableUsers extends JTable {
    
    public $id              = null;
    public $users_id        = null;
    public $fbuser_id       = null;
    public $twuser_id       = null;
    
    public function __construct( $db ) {
        parent::__construct( '#__itpc_users', 'id', $db );
    }
    
    /**
     * Loads a row from the database and binds the fields to the object properties
     *
     * @access  public
     * @param   mixed   Optional primary key.  If not specifed, the value of current key is used
     * @param   mixed   The name of the authorisation service - Facebook, Twitter, ....
     * @return  boolean True if successful
     */
    public function load( $oid=null, $service = null ) {
    	
    	// Load record by different ids
    	switch($service) {
    		case "facebook":
    			$this->_tbl_key = "fbuser_id";
    			break;

    		case "twitter":
                $this->_tbl_key = "twuser_id";
                break;
                
    		default:
    			$this->_tbl_key = "id";
    			break;
    	}
    	
        $k = $this->_tbl_key;

        if ($oid !== null) {
            $this->$k = $oid;
        }

        $oid = $this->$k;

        if ($oid === null) {
            return false;
        }
        $this->reset();

        $db =& $this->getDBO();

        $query = 'SELECT *'
        . ' FROM '.$this->_tbl
        . ' WHERE '.$this->_tbl_key.' = '.$db->Quote($oid);
        $db->setQuery( $query );
        
        $result = $db->loadAssoc();
        
        if ($result) {
            return $this->bind($result);
        } else {
            $this->setError( $db->getErrorMsg() );
            return false;
        }
    }
    
}