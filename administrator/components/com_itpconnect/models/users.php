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

class ModelUsers extends JModel {
    
    /**
	 * Data array
	 *
	 * @var array
	 */
	private $data = null;

	/**
	 * Data total
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
	
	/**
	 * The Constructor of the Users model
	 */
    public function  __construct() {
        
        parent::__construct();
        
        global $mainframe, $option;
		
    	// Get the pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . "." . $this->getName() . ".limitstart", 'limitstart', 0, 'int');
        
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
    }
    
    
    /**
	 * Method to get data
	 *
	 * @since 1.5
	 */
	public function getData()	{

	    $query         =   $this->buildQuery();
	    $this->data    =   $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
    
		return $this->data;
	}
	
	/**
	 * Method to get a pagination object of the records
	 *
	 * @access public
	 * @return integer
	 */
	public function getPagination()	{
	    
		// Lets load the content if it doesn't already exist
		if (empty($this->pagination))
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->pagination;
	}
    
	/**
	 * Method to get the total number of the records
	 *
	 * @access public
	 * @return integer
	 */
	public function getTotal()	{
		// Lets load the content if it doesn't already exist
		if (empty($this->total))
		{
			$query = $this->buildQuery();
			
			$this->total = $this->_getListCount($query);
		}

		return $this->total;
	}
	
	/**
	 * Building query
	 *
	 * @return string
	 */
	public function buildQuery()	{
	    
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->buildContentWhere();
		$orderby	= $this->buildContentOrderBy();
		
		$query  =   "
		  SELECT  
		      `#__itpc_users`.*,
		      `#__users`.`name`
          FROM 
              `#__itpc_users` 
          INNER JOIN
            `#__users` ON `#__users`.`id`=`#__itpc_users`.`users_id`";
		
        $query  .= $where;
        $query  .= $orderby; 
        
		return $query;
	}

    /**
     * Build 'order' part of query
     *
     * @return string
     */ 		
    private function buildContentOrderBy()	{
        
		global $mainframe, $option;
        $orderby = "";
        
        // Order
        $filterOrder       = $mainframe->getUserStateFromRequest($option . "." . $this->getName() . ".filter_order", 'filter_order', 'users_id', 'cmd');
        $filterOrderDir   = $mainframe->getUserStateFromRequest($option . "." . $this->getName() . ".filter_order_Dir", 'filter_order_Dir', 'asc', 'word');
        
        if($filterOrder AND $filterOrderDir){
            $orderby = ' ORDER BY ' . $filterOrder . ' ' . $filterOrderDir;
        }
        
        return $orderby;
	}
	
	/**
     * Build 'where' part of query
     *
     * @return string
     */ 
	private function buildContentWhere()	{
	
		global $mainframe, $option;
		
		$where = array();
		
		$filterSearch = $mainframe->getUserStateFromRequest($option . "." . $this->getName() . ".filter_search",   'filter_search');
		
	    if(!empty($filterSearch)){
	        
	        if(!is_numeric($filterSearch)){
                // prepare search
                $filterSearch = $this->_db->getEscaped($filterSearch, true);
                $filterSearch = $this->_db->Quote('%' . $filterSearch . '%', false);
                
                $where[] = " `#__users`.`name` LIKE " . $filterSearch;
	        } else {
                $where[] = " `#__itpc_users`.`fbuser_id`=" . $this->_db->Quote($filterSearch) . " OR " . "`#__itpc_users`.`twuser_id`=" . $this->_db->Quote($filterSearch);
	        }
            
        }
            
		$where = (count($where)) ? ' WHERE ' . implode(' AND ', $where) : '';

		return $where;
	}
    
    /**
     * Delete records from the DB
     *
     * @param array $cids
     * @exception ItpUserException
     * @exception ItpException
     */
    public function delete($cids) {
    	
        if(!$cids){
            throw new ItpUserException(JText::_('COM_ITPCONNECT_ERROR_INVALID_USERS_SELECTED'), 404);
        }
        
		// Delete categories 
		$query = "
			DELETE  
			FROM 
			     `#__itpc_users` 
			WHERE   
			     `id` IN ( ". implode( ',', $cids ) ." )";
		
		$this->_db->setQuery($query);
		
		if(!$this->_db->query()) {
			throw new ItpException($this->_db->getErrorMsg(), 500);
		}
			
    }
    
}