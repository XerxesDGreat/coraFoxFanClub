<?php
/**
 * @package      ITPrism Modules
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
defined ( '_JEXEC' ) or die ();

if(!class_exists('ItpcHelper')) {
    // Register Component helpers
    JLoader::register("ItpcHelper",JPATH_ROOT . DS. "administrator" . DS . "components" . DS ."com_itpconnect" . DS . "helpers" . DS . "itpchelper.php");
}

$params->def('greeting', 1);

$type       = ItpcHelper::getType();
$return     = ItpcHelper::getReturnURL($params, $type);
$user       =& JFactory::getUser();

$itpConnectParams = JComponentHelper::getParams('com_itpconnect');

if($itpConnectParams->get("facebookOn")){
    
    $facebook   = ItpcHelper::getFB();
    $session    = $facebook->getSession();
    
    $me = null;
    // Session based API call.
    if ($session) {
    	try {
    		$uid = $facebook->getUser ();
    		$me  = $facebook->api('/me');
    	} catch ( FacebookApiException $e ) {
    		$itpSecurity = new ItpSecurity($e);
    		$itpSecurity->AlertMe();
    	}
    }
}

require (JModuleHelper::getLayoutPath('mod_itpconnect'));