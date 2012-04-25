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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * It is a Settings model
 * 
 * @author Todor Iliev
 */
class ModelSettings extends JModel {
    
    /**
	 * Category ata array
	 *
	 * @var array
	 */
	private $data = null;

	/**
	 * Category total
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
	
    public function store( $params ) {
        
        if ( !$params ) {
        	throw new ItpException( JText::_( 'ITP_ERROR_INVALID_PARAMS'), 500 );
        }
        
        // Sets the parameters files
        $paramsDescFile   = JPATH_COMPONENT_ADMINISTRATOR.DS.'params.xml';
		
		$paramseters      = new JParameter( $params, $paramsDescFile );
		$paramseters->bind($params['facebook'],"facebook");
		//$paramseters->bind($params['twitter'],"twitter");
		
        $ini       = $paramseters->toString('INI', "facebook");
        //$ini       .= $paramseters->toString('INI', "twitter");
		
        $component = JComponentHelper::getComponent( "com_itpconnect" );
        
        $tableCategories    = $this->_db->nameQuote('#__components');
        $columnParams       = $this->_db->nameQuote('params');
        $columnId           = $this->_db->nameQuote('id');
        
        $query = "
            UPDATE
                $tableCategories
            SET
                $columnParams = " . $this->_db->Quote($ini) . "
            WHERE
                $columnId = " . (int)$component->id;
        
        $this->_db->setQuery( $query );
        
        if( !$this->_db->query() ) {
            throw new ItpException( $this->_db->getErrorMsg() , 500 );
        }
        
    }
    
}