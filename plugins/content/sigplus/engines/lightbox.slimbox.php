<?php
/**
* @file
* @brief    sigplus Image Gallery Plus Slimbox lightbox engine
* @author   Levente Hunyadi
* @version  1.3.4
* @remarks  Copyright (C) 2009-2011 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/sigplus
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Support class for Slimbox (MooTools-based).
* @see http://www.digitalia.be/software/slimbox
*/
class SIGPlusSlimboxEngine extends SIGPlusLightboxEngine {
	public function getIdentifier() {
		return 'slimbox';
	}

	protected function addCommonScripts() {
		$this->addMooTools();
		
		if (version_compare(JVERSION, '1.5.19') < 0 || version_compare(JVERSION, '1.6') < 0 && !JPluginHelper::isEnabled('system', 'mtupgrade')) {
			// use Slimbox compatible with MooTools 1.1
			parent::addCommonScripts();
		} else {
			// use Slimbox compatible with MooTools 1.2
			$this->addScript('/plugins/content/sigplus/engines/'.$this->getIdentifier().'/js/mtupgrade/'.$this->getScriptFilename());
		}
	}

	public function addScripts($galleryid, $params) {
		$this->addInitializationScripts();
		$script = 'bindSlimbox($("'.$galleryid.'"), '.$this->getCustomParameters($params).');';
		$this->addOnReadyScript($script);
	}
}
