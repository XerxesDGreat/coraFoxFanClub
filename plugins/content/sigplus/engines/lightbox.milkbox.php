<?php
/**
* @file
* @brief    sigplus Image Gallery Plus Milkbox lightbox engine
* @author   Levente Hunyadi
* @version  1.3.4
* @remarks  Copyright (C) 2009-2010 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/sigplus
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Support class for Milkbox (MooTools-based).
* @see http://reghellin.com/milkbox/
*/
class SIGPlusMilkboxEngine extends SIGPlusLightboxEngine {
	public function getIdentifier() {
		return 'milkbox';
	}

	protected function addCommonScripts() {
		if (version_compare(JVERSION, '1.5.19') < 0 || version_compare(JVERSION, '1.6') < 0 && !JPluginHelper::isEnabled('system', 'mtupgrade')) {
			throw new SIGPlusMooToolsException($this->getIdentifier());
		} else {
			// use Milkbox compatible with MooTools 1.2
			$this->addMooTools();
			parent::addCommonScripts();
		}
	}

	protected function addInitializationScripts() {
		$this->addCommonScripts();
		// suppress initialization script for Milkbox
	}

	public function addScripts($galleryid, $params) {
		if ($params->linkage != 'inline') {
			throw new SIGPlusNotSupportedException();
		}
	
		$this->addInitializationScripts();
		$script = 'milkbox.setAutoPlay({'.
			'gallery:"'.$galleryid.'",'.
			'delay:'.($params->slideshow/1000).
		'});';
		$this->addOnReadyScript($script);
	}
}
