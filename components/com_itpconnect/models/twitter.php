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
class ModelTwitter extends JModel {
    
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
	public function getData($fbUserId)	{
		return $this->data;
	}
	
}