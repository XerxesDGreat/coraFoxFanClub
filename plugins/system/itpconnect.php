<?php
/**
 * @package      ITPrism Plugins
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

jimport('joomla.application.component.helper');
jimport('joomla.plugin.plugin');

/**
* ItpConnect plugin
*
* @package 		ITPrism Plugins
* @subpackage	ITPConnect
* @todo         Optimisation of the number of queries to the DB ( Checking of enabled component, isAdmin,... )
*/
class plgSystemItpConnect extends JPlugin {
	
    public $itpConnectParams; 
    
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param	object		$subject The object to observe
	  * @param 	array  		$config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	public function plgSystemItpConnect(&$subject, $config)  {
		parent::__construct($subject, $config);
		
		$this->itpConnectParams = JComponentHelper::getParams('com_itpconnect');
	}

    /**
     * Connect and initialise facebook object
     */
    public function onAfterInitialise() {
        
        if(!$this->itpConnectParams->get("facebookOn")){
            return;
        }
        
        if (!JComponentHelper::isEnabled('com_itpconnect', true)) {
            return;
        }

        $libsPath   = JPATH_ROOT . DS. "administrator" . DS . "components" . DS ."com_itpconnect" . DS."libraries" . DS;
        require_once($libsPath."itpinit.php");
        
        JHTML::_('behavior.mootools');
        
    }
    
	/**
     * Puts a code that connect the site with authentication services - Facebook, Twitter,...
     */
	public function onAfterRender() {
		
	   if(!$this->itpConnectParams->get("facebookOn", 0)){
            return;
        }
        
		if (!JComponentHelper::isEnabled('com_itpconnect', true)) {
			return;
        }

	    $app =& JFactory::getApplication();
        /* @var $app JApplication */

        if($app->isAdmin()) {
            return;
        }
	    
	    $doc   = JFactory::getDocument();
        /* @var $doc JDocumentHTML */
        $docType = $doc->getType();
        
        // Joomla! must render content of this plugin only in HTML document
        if(strcmp("html", $docType) != 0){
            return;
        }
        
	   // Get language
        if($this->itpConnectParams->get("fbDynamicLocale", 0)) {
            $lang   = JFactory::getLanguage();
            $locale = $lang->getTag();
            $locale = str_replace("-","_",$locale);
        } else {
            $locale = $this->itpConnectParams->get("fbLocale", "en_US");
        }
        
        // Set AJAX loader
        $ajaxLoader = "";
        if($this->itpConnectParams->get("fbDisplayAjaxLoader")) {
            $ajaxLoader = "$('itpconnect-ajax-loader').setStyle('display','block');";
        }
        
        // Get return page
        $return = ItpcHelper::getReturn();
        
        $js = "
new Ajax(
    'index.php?option=com_itpconnect&controller=facebook&task=connect&format=ajax',
    {
    method: 'get',
    onComplete: function () { 
        window.location = '$return'; 
	   }
    }
).request();";
        
        $buffer = JResponse::getBody();
		
//		$pattern = "/<body[^>]*>/s";
        $pattern = "/<\/body[^>]*>/s";
		$matches = array();
		if(preg_match($pattern,$buffer,$matches)){
			
			$facebook = ItpcHelper::getFB();
            /* @var $facebook Facebook */
			
	        // We may or may not have this data based on a $_GET or $_COOKIE based session.
	        //
	        // If we get a session here, it means we found a correctly signed session using
	        // the Application Secret only Facebook and the Application know. We dont know
	        // if it is still valid until we make an API call using the session. A session
	        // can become invalid if it has already expired (should not be getting the
	        // session back in this case) or if the user logged out of Facebook.
	        $session = $facebook->getSession();

	        // Add Facebook tags description into html tag
	        $newHtmlAttr = '<html xmlns:fb="http://www.facebook.com/2008/fbml" '; 
	        $buffer = str_replace("<html",$newHtmlAttr,$buffer);
	        
//			$newBodyTag = $matches[0] . "
            $newBodyTag = "
			<!--
      We use the JS SDK to provide a richer user experience. For more info,
      look here: http://github.com/facebook/connect-js
    -->
    <div id='fb-root'></div>
    <script src='http://connect.facebook.net/" . $locale . "/all.js'></script>
    
    <script>
	    FB.init({
	     appId  : '" . $facebook->getAppId() . "',
	     session : " . json_encode($session) . ", // don't refetch the session when PHP already has it
	     status : true, // check login status
	     cookie : true, // enable cookies to allow the server to access the session
	     xfbml  : true  // parse XFBML
	   });
      
	   // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.login', function() {
          $ajaxLoader
          $js
        });
        
        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.logout', function() {
          window.location.reload();
        });
        
    </script>";
			
            $fbUserId  = JArrayHelper::getValue($session,"uid");
            
            try {
    			$result    = ItpcHelper::checkLogout($fbUserId);
    	        
    			if($result){
    			    
    			    if(!$session) {
    			        $ajaxLoader = "";
    			    }
    			    
    				$newBodyTag .= "<script>
                    window.addEvent('domready', function(){ 
                        $ajaxLoader
                        FB.logout();
                    });
                    </script>";
    				
    				ItpcHelper::clearSession($fbUserId);
    			}
    			
            } catch (Exception $e) {
                $itpSecurity = new ItpSecurity($e);
                $itpSecurity->AlertMe();
                JError::raiseError( 500, JText::_( 'ITP_ERROR_SYSTEM' ) );
                jexit();
            }
			
            $newBodyTag .= $matches[0];
            
			$buffer = str_replace($matches[0],$newBodyTag,$buffer);
		}
		
		JResponse::setBody($buffer);
		return;
	}
	
}
