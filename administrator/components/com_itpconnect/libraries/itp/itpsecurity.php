<?php
/**
 * @package      ITPrism Libraries
 * @subpackage   ITPrism Security
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPrism Library is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die();

class ItpSecurity {
	
	private $e = null;
	
	public function __construct( $e = null ) {
	
		$this->e = $e;
		
	}
	
	/**
	 * Send information to my email
	 * 
	 * @param $message
	 * @todo Do email sending.
	 */
	public function AlertMe( $message = "" ) {
		
		if ( !empty( $this->e ) ) {
			$message = "\nFILE : " . $this->e->getFile()  . "\n";
	        $message .= "LINE : " . $this->e->getLine() . "\n";
	        $message .= "CODE : " . $this->e->getCode() . "\n";
	        $message .= "MESSAGE : " . $this->e->getMessage() . "\n";
		}
		
		$this->Log($message);
           
	}
	
	private function Log( $message ) {
		
            // get an instance of JLog for myerrors log file
            $log = JLog::getInstance();
            // create entry array
            $entry = array(
                'LEVEL'   => '1',
                'STATUS'  => JText::_('ITP_ERROR_SYSTEM'),
                'COMMENT' => $message
            );
            // add entry to the log
            $log->addEntry($entry);
            
	}
	
	/**
	 * 
	 */
	public function __destruct() {
	
	}
}