<?php
/**
 * @package      ITPrism Libraries
 * @subpackage   ITPrism Initializators
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

jimport('joomla.error.log');

if(!defined("ITPCONNECT_COMPONENT_ADMINISTRATOR")) {
    define("ITPCONNECT_COMPONENT_ADMINISTRATOR", JPATH_ROOT . DS. "administrator" . DS . "components" . DS ."com_itpconnect");
}

// Register ITPrism library
JLoader::register("ItpResponse",ITPCONNECT_COMPONENT_ADMINISTRATOR . DS . "libraries" . DS . "itp". DS . "itpresponse.php");
JLoader::register("ItpSecurity",ITPCONNECT_COMPONENT_ADMINISTRATOR . DS . "libraries" . DS . "itp". DS . "itpsecurity.php");
JLoader::register("ItpException",ITPCONNECT_COMPONENT_ADMINISTRATOR . DS . "libraries" . DS . "itp". DS . "exceptions" . DS . "itpexception.php");
JLoader::register("ItpUserException",ITPCONNECT_COMPONENT_ADMINISTRATOR . DS . "libraries" . DS . "itp". DS . "exceptions" . DS . "itpuserexception.php");

// Register Component libraries
JLoader::register("ItpcVersion",ITPCONNECT_COMPONENT_ADMINISTRATOR . DS . "libraries" . DS . "itpconnect". DS . "itpcversion.php");
JLoader::register("Facebook",ITPCONNECT_COMPONENT_ADMINISTRATOR . DS . "libraries" . DS . "facebook". DS . "facebook.php");

// Register Component helpers
JLoader::register("ItpcHelper",ITPCONNECT_COMPONENT_ADMINISTRATOR . DS . "helpers" . DS . "itpchelper.php");