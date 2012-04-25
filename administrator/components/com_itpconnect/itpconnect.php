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

// Require the base controller
require_once (JPATH_COMPONENT . DS . 'controller.php');

require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . "libraries" . DS . "itpinit.php");

$defaultController = "cp";

$c = JRequest::getCmd('controller', $defaultController);

// get controller
if(!empty($c)){
    
    //determine path
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $c . '.php';
    jimport('joomla.filesystem.file');
    
    if(JFile::exists($path)){
        // controller exists, get it!
        require_once ($path);
    
    }else{
        
        // Require the base controller
        require_once JPATH_COMPONENT . DS . 'controller.php';
        $c = $defaultController;
    }

}

// instantiate and execute the controller
$c = $c . 'ControllerItpConnect';
$controller = new $c();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();